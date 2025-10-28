<?php
session_start();

$secretKey = getenv('PAYMONGO_SECRET');
if (!$secretKey) {
    $paymentConfigPath = __DIR__ . '/config/payment.php';
    if (file_exists($paymentConfigPath)) {
        require $paymentConfigPath;
        if (isset($PAYMONGO_SECRET) && is_string($PAYMONGO_SECRET) && $PAYMONGO_SECRET !== '') {
            $secretKey = $PAYMONGO_SECRET;
        }
    }
}
if (!$secretKey) {
    echo 'Payment configuration missing.';
    exit;
}

$ref = $_GET['ref'] ?? null;
$sessionId = $_GET['id'] ?? $_GET['checkout_session_id'] ?? ($_SESSION['checkout_session_id'] ?? null);
// If session id missing, attempt to load via ref file
if (!$sessionId && $ref) {
    $tmpFile = __DIR__ . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'pending_' . basename($ref) . '.json';
    if (is_file($tmpFile)) {
        $tmp = json_decode(file_get_contents($tmpFile), true);
        if (isset($tmp['checkout_session_id'])) {
            $sessionId = $tmp['checkout_session_id'];
        }
        if (!isset($_SESSION['pending_booking']) && isset($tmp['pending_booking'])) {
            $_SESSION['pending_booking'] = $tmp['pending_booking'];
        }
    }
}
if (!$sessionId) {
    echo 'Invalid payment session.';
    exit;
}

// Verify Checkout Session status
$ch = curl_init('https://api.paymongo.com/v1/checkout_sessions/' . urlencode($sessionId));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($secretKey . ':'),
]);
$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $statusCode < 200 || $statusCode >= 300) {
    echo 'Failed to verify payment.';
    exit;
}

$data = json_decode($response, true);
$attributes = $data['data']['attributes'] ?? [];

// Consider multiple sources of truth for a successful payment
$paid = false;
// 1) Checkout session status (some versions return 'paid' or 'completed')
if (isset($attributes['status']) && in_array($attributes['status'], ['paid', 'succeeded', 'completed'])) {
    $paid = true;
}
// 2) Any captured/paid payment in the payments array
if (!$paid && isset($attributes['payments']) && is_array($attributes['payments'])) {
    foreach ($attributes['payments'] as $p) {
        $pa = $p['attributes'] ?? [];
        if (
            (isset($pa['status']) && in_array($pa['status'], ['paid', 'succeeded', 'succeeded'])) ||
            (!empty($pa['paid_at']))
        ) {
            $paid = true;
            break;
        }
    }
}

if (!$paid) {
    echo 'Payment not completed.';
    exit;
}

// Mark payment verified and save booking
$_SESSION['payment_verified'] = true;

// Persist successful transaction to DB
require_once __DIR__ . '/config/config.php';

$status = $attributes['status'] ?? 'paid';
$payments = $attributes['payments'] ?? [];

// Try to extract payment_intent_id from the first payment, else fallback to session id
$paymentIntentId = null;
if (is_array($payments) && !empty($payments)) {
    $firstPayment = $payments[0] ?? [];
    $paymentAttributes = $firstPayment['attributes'] ?? [];
    if (isset($paymentAttributes['payment_intent_id'])) {
        $paymentIntentId = $paymentAttributes['payment_intent_id'];
    } elseif (isset($firstPayment['id'])) {
        $paymentIntentId = $firstPayment['id'];
    }
}
if (!$paymentIntentId) {
    $paymentIntentId = $data['data']['id'] ?? $sessionId;
}

// Amount from PayMongo if available; it is in centavos
$amount = null;
if (isset($attributes['amount_total'])) {
    $amount = ((float) $attributes['amount_total']) / 100.0;
}

// Fallback amount from pending booking (server-side computation)
if ($amount === null && isset($_SESSION['pending_booking'])) {
    $activityName = $_SESSION['pending_booking']['activity_name'] ?? '';
    $numParticipants = (int) ($_SESSION['pending_booking']['num_of_participants'] ?? 1);
    $feePerHead = match ($activityName) {
        'Pickleball' => 100,
        'Badminton' => 80,
        default => 0,
    };
    $amount = (float) ($feePerHead * $numParticipants);
}

$currency = 'PHP';
$description = 'Court Booking';
if (isset($_SESSION['pending_booking'])) {
    $an = $_SESSION['pending_booking']['activity_name'] ?? '';
    $np = $_SESSION['pending_booking']['num_of_participants'] ?? '';
    $description = trim(($an ? ($an . ' ') : '') . ($np ? ('x' . $np) : '')) ?: 'Court Booking';
}

// Fetch user details for transaction (fallback to session name/email if available)
$txName = $_SESSION['name'] ?? '';
$txEmail = $_SESSION['email'] ?? '';
$txContact = '';
if (isset($_SESSION['user_id'])) {
    $uid = (int) $_SESSION['user_id'];
    $usr = $conn->prepare("SELECT name, email, phone FROM users WHERE user_id = ? LIMIT 1");
    if ($usr) {
        $usr->bind_param("i", $uid);
        $usr->execute();
        $usrRes = $usr->get_result();
        if ($row = $usrRes->fetch_assoc()) {
            $txName = $row['name'] ?? $txName;
            $txEmail = $row['email'] ?? $txEmail;
            $txContact = $row['phone'] ?? '';
        }
        $usr->close();
    }
}

$stmt = $conn->prepare("INSERT INTO transactions (payment_intent_id, name, email, contact_number, amount, currency, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("ssssdsss", $paymentIntentId, $txName, $txEmail, $txContact, $amount, $currency, $description, $status);
    $stmt->execute();
    $stmt->close();
}

if (!isset($_SESSION['pending_booking'])) {
    echo 'No pending booking found.';
    exit;
}

// Rehydrate session user if present in pending payload
if (isset($_SESSION['pending_booking']['user_id']) && $_SESSION['pending_booking']['user_id']) {
    $_SESSION['user_id'] = (int) $_SESSION['pending_booking']['user_id'];
}

// Directly create the booking here as CONFIRMED to avoid relying on JSON saver
$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$title = $_SESSION['pending_booking']['title'] ?? '';
$description = $_SESSION['pending_booking']['description'] ?? '';
$activity_name = $_SESSION['pending_booking']['activity_name'] ?? '';
$court_number = (int) ($_SESSION['pending_booking']['court_number'] ?? 1);
$num_of_participants = (int) ($_SESSION['pending_booking']['num_of_participants'] ?? 1);
$event_date = $_SESSION['pending_booking']['event_date'] ?? date('Y-m-d');
$event_time = $_SESSION['pending_booking']['event_time'] ?? date('H:i:s');

$fee_per_head = match ($activity_name) {
    'Pickleball' => 40,
    'Badminton' => 30,
    default => 0,
};
$total_fee = $fee_per_head * $num_of_participants;
$statusBooking = 'CONFIRMED';

$bstmt = $conn->prepare("INSERT INTO bookings (user_id, title, description, activity_name, court_number, num_of_participants, fee_per_head, total_fee, event_date, event_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($bstmt) {
    $bstmt->bind_param("isssiiddsss", $user_id, $title, $description, $activity_name, $court_number, $num_of_participants, $fee_per_head, $total_fee, $event_date, $event_time, $statusBooking);
    $bstmt->execute();
    $bstmt->close();
}

// Cleanup
unset($_SESSION['payment_verified'], $_SESSION['pending_booking'], $_SESSION['payment_initiated'], $_SESSION['checkout_session_id']);
if (!empty($ref)) {
    $tmpFile = __DIR__ . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'pending_' . basename($ref) . '.json';
    if (is_file($tmpFile)) {
        @unlink($tmpFile);
    }
}

// Redirect back to schedule page
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = rtrim($scheme . '://' . $host . $base, '/');
header('Location: ' . $baseUrl . '/schedule.php?paid=1');
exit;



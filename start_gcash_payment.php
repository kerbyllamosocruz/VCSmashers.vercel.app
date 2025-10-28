<?php
header('Content-Type: application/json');
session_start();
require_once "config/MongoSecretKey.php";

// $secretKey = getenv('PAYMONGO_SECRET'); // USE THIS IN PRODUCTION. HARDCODED FOR TESTING ONLY.
if (!$secretKey) {
    echo json_encode(['status' => 'error', 'message' => 'Payment configuration missing.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

$pendingBooking = [
    'user_id' => isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null,
    'title' => $_POST['title'] ?? '',
    'description' => $_POST['description'] ?? '',
    'activity_name' => $_POST['activity_name'] ?? '',
    'court_number' => $_POST['court_number'] ?? 1,
    'num_of_participants' => $_POST['num_of_participants'] ?? 1,
    'event_date' => $_POST['event_date'] ?? date('Y-m-d'),
    'event_time' => $_POST['event_time'] ?? date('H:i:s'),
];

$activityName = $pendingBooking['activity_name'];
$numParticipants = (int) $pendingBooking['num_of_participants'];
$feePerHead = match ($activityName) {
    'Pickleball' => 40,
    'Badminton' => 30,
    default => 0,
};
$totalFee = $feePerHead * $numParticipants;

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = rtrim($scheme . '://' . $host . $base, '/');
$ref = bin2hex(random_bytes(16));
$successUrl = $baseUrl . '/payment_success.php?ref=' . urlencode($ref);
$cancelUrl = $baseUrl . '/payment_cancel.php';

$amountCentavos = (int) round($totalFee * 100);

$payload = [
    'data' => [
        'attributes' => [
            'cancel_url' => $cancelUrl,
            'success_url' => $successUrl,
            'payment_method_types' => ['gcash'],
            'line_items' => [
                [
                    'name' => $activityName ?: 'Court Booking',
                    'amount' => $amountCentavos,
                    'currency' => 'PHP',
                    'quantity' => 1,
                ],
            ],
        ],
    ],
];

$ch = curl_init('https://api.paymongo.com/v1/checkout_sessions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($secretKey . ':'),
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to initiate payment.']);
    exit;
}

$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$json = json_decode($response, true);
if ($statusCode >= 200 && $statusCode < 300 && isset($json['data']['attributes']['checkout_url'])) {
    $_SESSION['payment_initiated'] = true;
    if (isset($json['data']['id'])) {
        $_SESSION['checkout_session_id'] = $json['data']['id'];
    }
    $tmpDir = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
    if (!is_dir($tmpDir)) {
        @mkdir($tmpDir, 0777, true);
    }
    $payloadToSave = [
        'ref' => $ref,
        'checkout_session_id' => $json['data']['id'] ?? null,
        'pending_booking' => $pendingBooking,
        'amount_php' => $totalFee,
    ];
    @file_put_contents($tmpDir . DIRECTORY_SEPARATOR . 'pending_' . $ref . '.json', json_encode($payloadToSave));
    $_SESSION['pending_booking'] = $pendingBooking;
    echo json_encode(['status' => 'ok', 'checkout_url' => $json['data']['attributes']['checkout_url']]);
    exit;
}

$message = $json['errors'][0]['detail'] ?? 'Payment service error.';
echo json_encode(['status' => 'error', 'message' => $message]);
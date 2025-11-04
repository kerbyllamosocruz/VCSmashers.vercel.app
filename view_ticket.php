<?php
session_start();
require_once __DIR__ . '/config/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Get current user's ID and role
$current_user_id = (int)$_SESSION['user_id'];
$current_user_role = $_SESSION['role_id'] ?? 2; // Default to regular user (2) if not set

// Get booking ID and validate
$booking_id = isset($_GET['booking_id']) ? (int) $_GET['booking_id'] : 0;
if (!$booking_id) {
    header("Location: profile_page.php");
    exit;
}

// Validate access token if provided
$access_token = $_GET['access_token'] ?? '';
$has_valid_token = false;

if ($access_token && isset($_SESSION['receipt_access'])) {
    $stored = $_SESSION['receipt_access'];
    if ($stored['token'] === $access_token && 
        $stored['booking_id'] === $booking_id && 
        $stored['expiry'] > time()) {
        $has_valid_token = true;
        // Clear the token after use
        unset($_SESSION['receipt_access']);
    }
}

// Fetch booking details (assumes bookings table has `booking_id` primary key)
$stmt = $conn->prepare("SELECT booking_id, user_id, title, description, activity_name, court_number, num_of_participants, fee_per_head, total_fee, event_date, event_time, event_end_time, status FROM bookings WHERE booking_id = ? LIMIT 1");
if (!$stmt) {
    echo "<p style='padding:20px;'>Database error: could not prepare statement.</p>";
    exit;
}
$stmt->bind_param('i', $booking_id);
$stmt->execute();
$res = $stmt->get_result();
$booking = $res->fetch_assoc();
$stmt->close();

if (!$booking) {
    header("Location: profile_page.php?error=booking_not_found");
    exit;
}

// Check if user has permission to view this receipt
// Allow if: 
// 1. User has valid access token from payment success, OR
// 2. User is an admin (role_id = 1), OR
// 3. User owns this booking
if (!$has_valid_token && $current_user_role !== 1 && $booking['user_id'] !== $current_user_id) {
    header("Location: profile_page.php?error=unauthorized");
    exit;
}

// Fetch user info if available
$customerName = $_SESSION['name'] ?? '';
$customerEmail = $_SESSION['email'] ?? '';
$customerPhone = '';
if (!empty($booking['user_id'])) {
    $uid = (int) $booking['user_id'];
    $u = $conn->prepare("SELECT name, email, phone FROM users WHERE user_id = ? LIMIT 1");
    if ($u) {
        $u->bind_param('i', $uid);
        $u->execute();
        $ur = $u->get_result();
        if ($row = $ur->fetch_assoc()) {
            $customerName = $row['name'] ?? $customerName;
            $customerEmail = $row['email'] ?? $customerEmail;
            $customerPhone = $row['phone'] ?? $customerPhone;
        }
        $u->close();
    }
}

// Small helper to format date/time
$displayDate = date('d-M-Y', strtotime($booking['event_date'] ?? ''));
$displayStartTime = date('g:i A', strtotime($booking['event_time'] ?? ''));
$displayEndTime = date('g:i A', strtotime($booking['event_end_time'] ?? ''));

// Look up the most recent transaction for this user/email with matching amount (best-effort)
$transactionId = 'N/A';
if (!empty($customerEmail) && isset($booking['total_fee'])) {
    // Try to find matching transaction by email and amount
    $t = $conn->prepare("SELECT payment_intent_id FROM transactions WHERE email = ? AND amount = ? LIMIT 1");
    if ($t) {
        $amount = (float) $booking['total_fee'];
        $t->bind_param('sd', $customerEmail, $amount);
        $t->execute();
        $tr = $t->get_result();
        if ($r = $tr->fetch_assoc()) {
            $transactionId = $r['payment_intent_id'] ?? 'N/A';
        }
        $t->close();
    }
}

// Render the receipt page (simple, printable, and downloadable via html2canvas)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Booking #<?php echo htmlspecialchars($booking['booking_id']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
        .receipt-container::before,
        .receipt-container::after {
            content: '';
            display: block;
            width: 100%;
            height: 15px;
            background-image: linear-gradient(to right, #a0aec0 33%, rgba(255,255,255,0) 0%);
            background-position: bottom;
            background-size: 6px 2px;
            background-repeat: repeat-x;
            position: absolute;
            left: 0;
        }
        .receipt-container::before { top: -10px; }
        .receipt-container::after { bottom: -10px; }
        @media print { .no-print { display:none; } }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-xl mx-auto">
        <div id="receipt-container" class="receipt-container relative mb-6">
            <div id="receipt" class="bg-white text-gray-900 p-8 rounded-lg shadow-lg w-full">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-primary">Maysan Badminton Court</h1>
                    <p class="text-sm text-gray-500">OFFICIAL RECEIPT</p>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span class="font-mono font-bold"><?php echo htmlspecialchars($transactionId); ?></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Date Paid:</span>
                        <span class="font-mono font-bold"><?php echo date('d-M-Y'); ?></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Paid By:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($customerName ?: 'Guest'); ?></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-300 my-4"></div>

                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-2">Reservation Details</h2>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Activity / Title:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($booking['title']); ?></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Court:</span>
                        <span class="font-bold">Court <?php echo htmlspecialchars($booking['court_number']); ?></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($displayDate); ?></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-bold"><?php echo htmlspecialchars($displayStartTime . ' - ' . $displayEndTime); ?></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-bold"><?php 
                            $start = strtotime($booking['event_time'] ?? '');
                            $end = strtotime($booking['event_end_time'] ?? '');
                            $duration = round(($end - $start) / 3600); // Convert seconds to hours
                            echo $duration . ' hour' . ($duration > 1 ? 's' : '');
                        ?></span>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-300 my-4"></div>

                <div class="mb-6">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span>Total Paid:</span>
                        <span class="text-green-600">PHP <?php echo number_format((float)$booking['total_fee'], 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-bold">GCASH Payment</span>
                    </div>
                </div>

                <div class="relative text-center">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-6xl font-black text-green-500 opacity-20 transform -rotate-12 select-none">CONFIRMED</span>
                    </div>
                    <p class="text-gray-500 italic">Thank you for your reservation!</p>
                </div>
            </div>
        </div>

        <div class="no-print space-y-3">
            <a href="profile_page.php" class="inline-block text-sm px-4 py-2 bg-gray-200 rounded">Back to Profile</a>
            <button id="downloadBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition">Download Receipt (PNG)</button>
        </div>
    </div>

    <script>
        const downloadBtn = document.getElementById('downloadBtn');
        const receiptElement = document.getElementById('receipt');

        downloadBtn.addEventListener('click', () => {
            const original = downloadBtn.innerHTML;
            downloadBtn.innerHTML = 'Generating...';
            downloadBtn.disabled = true;
            // Use html2canvas to capture the receipt
            html2canvas(receiptElement, { scale: 2, useCORS: true }).then(canvas => {
                const dataUrl = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = 'receipt-booking-<?php echo htmlspecialchars($booking['booking_id']); ?>.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                downloadBtn.innerHTML = original;
                downloadBtn.disabled = false;
            }).catch(err => {
                console.error('html2canvas error', err);
                downloadBtn.innerHTML = 'Error - Try again';
                downloadBtn.disabled = false;
                setTimeout(() => downloadBtn.innerHTML = original, 2000);
            });
        });
    </script>
</body>
</html>

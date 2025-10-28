<?php
session_start();
require_once "config/config.php";

// Security check: must be logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in to view receipts']);
    exit;
}

// Validate booking_id
$booking_id = isset($_GET['booking_id']) ? (int) $_GET['booking_id'] : 0;
if (!$booking_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid booking ID']);
    exit;
}

// Security check: fetch booking and verify it belongs to current user
$stmt = $conn->prepare("SELECT b.*, u.name, u.email, u.phone 
    FROM bookings b 
    LEFT JOIN users u ON b.user_id = u.user_id 
    WHERE b.booking_id = ? AND b.user_id = ? 
    LIMIT 1");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$stmt->bind_param('ii', $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    http_response_code(404);
    echo json_encode(['error' => 'Booking not found or access denied']);
    exit;
}

// Format data for receipt
$receipt = [
    'booking_id' => $booking['booking_id'],
    'transaction_id' => 'N/A', // Will update if found
    'customer_name' => $booking['name'] ?? $_SESSION['name'] ?? 'Guest',
    'customer_email' => $booking['email'] ?? $_SESSION['email'] ?? '',
    'title' => $booking['title'],
    'activity_name' => $booking['activity_name'],
    'court_number' => $booking['court_number'],
    'event_date' => date('d-M-Y', strtotime($booking['event_date'])),
    'event_time' => date('g:i A', strtotime($booking['event_time'])),
    'total_fee' => number_format((float)$booking['total_fee'], 2),
    'status' => $booking['status'],
    'date_now' => date('d-M-Y')
];

// Try to find transaction
if (!empty($receipt['customer_email'])) {
    $t = $conn->prepare("SELECT payment_intent_id FROM transactions WHERE email = ? AND amount = ? LIMIT 1");
    if ($t) {
        $amount = (float) $booking['total_fee'];
        $t->bind_param('sd', $receipt['customer_email'], $amount);
        $t->execute();
        $tr = $t->get_result();
        if ($r = $tr->fetch_assoc()) {
            $receipt['transaction_id'] = $r['payment_intent_id'];
        }
        $t->close();
    }
}

// Return receipt data as JSON
header('Content-Type: application/json');
echo json_encode(['success' => true, 'receipt' => $receipt]);
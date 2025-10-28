<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config/config.php";

// If payment verified and user_id provided in pending payload/POST, restore it
if (empty($_SESSION['user_id']) && !empty($_SESSION['payment_verified'])) {
    if (!empty($_POST['user_id'])) {
        $_SESSION['user_id'] = (int) $_POST['user_id'];
    } elseif (!empty($_SESSION['pending_booking']['user_id'])) {
        $_SESSION['user_id'] = (int) $_SESSION['pending_booking']['user_id'];
    }
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Guard: only allow save when payment is verified
    if (empty($_SESSION['payment_verified'])) {
        echo json_encode(['status' => 'error', 'message' => 'Payment not verified.']);
        exit;
    }
    $user_id = (int) $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $activity_name = $_POST['activity_name'] ?? '';
    $court_number = (int) ($_POST['court_number'] ?? 1);
    $num_of_participants = (int) ($_POST['num_of_participants'] ?? 1);
    $event_date = $_POST['event_date'] ?? date('Y-m-d');
    $event_time = $_POST['event_time'] ?? date('H:i:s');

    $fee_per_head = match ($activity_name) {
        'Pickleball' => 100,
        'Badminton' => 80,
        default => 0,
    };
    $total_fee = $fee_per_head * $num_of_participants;

    $status = 'CONFIRMED';
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, title, description, activity_name, court_number, num_of_participants, fee_per_head, total_fee, event_date, event_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // Types: i s s s i i d d s s s
    $stmt->bind_param("isssiiddsss", $user_id, $title, $description, $activity_name, $court_number, $num_of_participants, $fee_per_head, $total_fee, $event_date, $event_time, $status);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Booking successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Booking failed.']);
    }
    $stmt->close();
    $conn->close();
}

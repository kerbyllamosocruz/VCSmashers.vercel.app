<?php
session_start();
require_once "config/config.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$bookingId = $input['booking_id'] ?? null;

if (!$bookingId) {
    $response['message'] = 'Booking ID is missing.';
    echo json_encode($response);
    exit;
}

// Prepare and execute the update statement
$stmt = $conn->prepare("UPDATE bookings SET status = 'CANCELLED' WHERE booking_id = ? AND user_id = ?");
$stmt->bind_param("ii", $bookingId, $userId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Booking cancelled successfully.';
    } else {
        $response['message'] = 'Booking not found or already cancelled.';
    }
} else {
    $response['message'] = 'Database error: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
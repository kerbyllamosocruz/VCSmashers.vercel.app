<?php
session_start();
require_once "config/config.php";

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'An error occurred.'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
        $response['message'] = 'OTP not verified.';
        echo json_encode($response);
        exit;
    }

    $password = trim($_POST["new_password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");

    if (empty($password) || empty($confirm_password)) {
        $response['message'] = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $response['message'] = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'Password must be at least 6 characters long.';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION['email_for_reset'];

        $stmt = $conn->prepare("UPDATE users SET pass = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        
        if ($stmt->execute()) {
            unset($_SESSION['otp_verified']);
            unset($_SESSION['email_for_reset']);
            unset($_SESSION['otp']);
            unset($_SESSION['otp_expiry']);
            $response['success'] = true;
            $response['message'] = 'Password has been reset successfully.';
        } else {
            $response['message'] = 'Failed to reset password. Please try again.';
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>

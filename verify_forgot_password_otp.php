<?php
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'An error occurred.'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = trim($_POST["otp"] ?? "");

    if (empty($otp)) {
        $response['message'] = 'Please enter the OTP.';
    } elseif (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
        $response['message'] = 'OTP has not been sent or has expired. Please try again.';
    } elseif ($_SESSION['otp_expiry'] < time()) {
        $response['message'] = 'OTP has expired. Please request a new one.';
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
    } elseif ($otp != $_SESSION['otp']) {
        $response['message'] = 'Invalid OTP.';
    } else {
        $_SESSION['otp_verified'] = true;
        $response['success'] = true;
        $response['message'] = 'OTP verified successfully.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = isset($_POST["otp"]) ? trim($_POST["otp"]) : '';

    if ($otp === '') {
        echo json_encode(["status" => "error", "message" => "Please enter the OTP."]);
        exit;
    }

    if (!isset($_SESSION['otp']) || !isset($_SESSION['registration_data'])) {
        echo json_encode(["status" => "error", "message" => "Session expired. Please try registering again."]);
        exit;
    }

    if (time() - $_SESSION['otp_time'] > 600) {
        echo json_encode(["status" => "error", "message" => "OTP has expired. Please request a new one."]);
        unset($_SESSION['otp']);
        unset($_SESSION['otp_time']);
        unset($_SESSION['registration_data']);
        exit;
    }

    if ($_SESSION['otp'] == $otp) {
        $reg_data = $_SESSION['registration_data'];
        $role_id = 2;

        $stmt = $conn->prepare("INSERT INTO users (role_id, name, email, phone, pass) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("issss", $role_id, $reg_data['name'], $reg_data['email'], $reg_data['phone'], $reg_data['pass']);
        if ($stmt->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['otp_time']);
            unset($_SESSION['registration_data']);
            echo json_encode(["status" => "success", "message" => "Registered successfully! Please login."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
    }

    $conn->close();
    exit;
}
?>
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';
    $password_raw = isset($_POST["pass"]) ? $_POST["pass"] : (isset($_POST["password"]) ? $_POST["password"] : '');

    if ($name === '' || $email === '' || $password_raw === '') {
        echo json_encode(["status" => "error", "message" => "Name, email, and password are required."]);
        exit;
    }

    $pass  = password_hash($password_raw, PASSWORD_DEFAULT);
    $role_id = 2;

    // Check existing email
    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (role_id, name, email, phone, pass) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("issss", $role_id, $name, $email, $phone, $pass);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registered successfully! Please login."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
        }
        $stmt->close();
    }

    $check->close();
}

$conn->close();
?>

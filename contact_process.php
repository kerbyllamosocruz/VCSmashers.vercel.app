<?php
session_start();
require_once "config/config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = ["success" => false, "message" => "An error occurred."];
    $cooldown_period = 300; // seconds

    if (isset($_SESSION['last_submission_time'])) {
        $time_since_last_submission = time() - $_SESSION['last_submission_time'];
        if ($time_since_last_submission < $cooldown_period) {
            $time_remaining = $cooldown_period - $time_since_last_submission;
            $response["message"] = "Please wait " . $time_remaining . " seconds before sending another message.";
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (empty($name) || empty($email) || empty($message)) {
        $response["message"] = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["message"] = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $_SESSION['last_submission_time'] = time();
            $response["success"] = true;
            $response["message"] = "Your message has been sent successfully!";
        } else {
            $response["message"] = "Failed to send message. Please try again later.";
        }

        $stmt->close();
        $conn->close();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
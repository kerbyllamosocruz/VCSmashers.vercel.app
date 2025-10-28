<?php
require_once "config/config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");

    $response = ["success" => false, "message" => "An error occurred."];

    if (empty($name) || empty($email) || empty($message)) {
        $response["message"] = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["message"] = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
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

<?php
// register.php

// Database connection settings
$host = "localhost";       // or 127.0.0.1
$user = "root";            // your MySQL username
$pass = "";                // your MySQL password
$db   = "VC_Smashers_CourtHubDB"; // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password_raw = $_POST["pass"];
    $pass  = password_hash($password_raw, PASSWORD_DEFAULT); // hashed password

    // Default role_id = 2 (USER)
    $role_id = 2;

    // Check if email already exists
    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (role_id, name, email, phone, pass) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("issss", $role_id, $name, $email, $phone, $pass);

        if ($stmt->execute()) {
            echo "Registration successful! <a href='login.html'>Login Here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check->close();
}

$conn->close();
?>

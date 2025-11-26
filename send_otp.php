<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/mail.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST["name"]) ? trim($_POST["name"]) : '';
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : '';
    $password_raw = isset($_POST["pass"]) ? $_POST["pass"] : (isset($_POST["password"]) ? $_POST["password"] : '');

    if ($name === '' || $email === '' || $password_raw === '') {
        echo json_encode(["status" => "error", "message" => "Name, email, and password are required."]);
        exit;
    }

    $passwordLength = strlen($password_raw);
    if ($passwordLength < 6 || $passwordLength > 18) {
        echo json_encode(["status" => "error", "message" => "Password must be 6-18 characters long."]);
        exit;
    }

    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
        exit;
    }
    $check->close();

    //  OTP
    $otp = rand(100000, 999999);

    $_SESSION['registration_data'] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'pass' => password_hash($password_raw, PASSWORD_DEFAULT)
    ];
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        $mail->setFrom('noreply@redsoiltradinghardware.store', 'Maysan Badminton Court');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Registration';
        $mail->Body = "Your OTP is: <b>$otp</b>. It will expire in 10 minutes.";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "An OTP has been sent to your email."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
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

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Please sign in to change your password.']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
  exit;
}

$current = trim($_POST['current_password'] ?? '');
$new = trim($_POST['new_password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

if ($current === '' || $new === '' || $confirm === '') {
  echo json_encode(['status' => 'error', 'message' => 'All password fields are required.']);
  exit;
}

if ($new !== $confirm) {
  echo json_encode(['status' => 'error', 'message' => 'New passwords do not match.']);
  exit;
}

$newLength = strlen($new);
if ($newLength < 6 || $newLength > 18) {
  echo json_encode(['status' => 'error', 'message' => 'New password must be 6-18 characters long.']);
  exit;
}

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT pass, email, name FROM users WHERE user_id = ?");
if (!$stmt) {
  echo json_encode(['status' => 'error', 'message' => 'Database error.']);
  exit;
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($hash, $email, $name);

if (!$stmt->fetch()) {
  $stmt->close();
  echo json_encode(['status' => 'error', 'message' => 'Account not found.']);
  exit;
}
$stmt->close();

if (!password_verify($current, $hash)) {
  echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
  exit;
}

if (password_verify($new, $hash)) {
  echo json_encode(['status' => 'error', 'message' => 'New password must be different from the current password.']);
  exit;
}

$otp = rand(100000, 999999);

$_SESSION['password_change'] = [
  'otp' => $otp,
  'otp_time' => time(),
  'new_hash' => password_hash($new, PASSWORD_DEFAULT),
  'user_id' => $userId
];

try {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = SMTP_HOST;
  $mail->SMTPAuth = true;
  $mail->Username = SMTP_USERNAME;
  $mail->Password = SMTP_PASSWORD;
  $mail->SMTPSecure = SMTP_SECURE;
  $mail->Port = SMTP_PORT;

  $mail->setFrom('noreply@redsoiltradinghardware.store', 'Maysan Badminton Court');
  $mail->addAddress($email, $name ?: 'Player');

  $mail->isHTML(true);
  $mail->Subject = 'OTP to Confirm Password Change';
  $mail->Body = "Hello " . htmlspecialchars($name ?: 'Player') . ",<br><br>Your OTP for changing your password is: <b>$otp</b>.<br>This code will expire in 10 minutes.";

  $mail->send();

  $maskedEmail = mask_email($email);
  echo json_encode([
    'status' => 'success',
    'message' => 'OTP sent to your email.',
    'maskedEmail' => $maskedEmail
  ]);
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => 'Unable to send OTP. Please try again later.']);
}

function mask_email($email)
{
  if (!$email || strpos($email, '@') === false) {
    return $email;
  }
  [$local, $domain] = explode('@', $email, 2);
  if (strlen($local) <= 2) {
    return substr($local, 0, 1) . str_repeat('*', max(0, strlen($local) - 1)) . '@' . $domain;
  }
  $maskedLocal = substr($local, 0, 1) . str_repeat('*', strlen($local) - 2) . substr($local, -1);
  return $maskedLocal . '@' . $domain;
}


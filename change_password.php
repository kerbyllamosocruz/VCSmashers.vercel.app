<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Please sign in to continue.']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
  exit;
}

$otp = trim($_POST['otp'] ?? '');
if ($otp === '') {
  echo json_encode(['status' => 'error', 'message' => 'Please provide the OTP.']);
  exit;
}

if (!isset($_SESSION['password_change'])) {
  echo json_encode(['status' => 'error', 'message' => 'No password change request found. Please request a new OTP.']);
  exit;
}

$changeData = $_SESSION['password_change'];

if (($changeData['user_id'] ?? null) !== $_SESSION['user_id']) {
  echo json_encode(['status' => 'error', 'message' => 'OTP does not match this account.']);
  exit;
}

if (time() - ($changeData['otp_time'] ?? 0) > 600) {
  unset($_SESSION['password_change']);
  echo json_encode(['status' => 'error', 'message' => 'OTP expired. Please request a new one.']);
  exit;
}

if ((string) ($changeData['otp'] ?? '') !== $otp) {
  echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
  exit;
}

$newHash = $changeData['new_hash'] ?? null;
if (!$newHash) {
  echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
  exit;
}

$stmt = $conn->prepare("UPDATE users SET pass = ? WHERE user_id = ?");
if (!$stmt) {
  echo json_encode(['status' => 'error', 'message' => 'Database error.']);
  exit;
}
$stmt->bind_param("si", $newHash, $_SESSION['user_id']);

if ($stmt->execute()) {
  unset($_SESSION['password_change']);
  echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Unable to update password. Please try again.']);
}

$stmt->close();
$conn->close();


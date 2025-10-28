<?php
session_start();
require_once "config/config.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'];
  $current = $_POST['current_password'] ?? '';
  $new = $_POST['new_password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';

  if ($new === '' || $current === '' || $confirm === '') {
    header("Location: profile_page.php?status=error&message=" . urlencode('All password fields are required.'));
    exit();
  }
  if ($new !== $confirm) {
    header("Location: profile_page.php?status=error&message=" . urlencode('New passwords do not match.'));
    exit();
  }
  
  $stmt = $conn->prepare("SELECT pass FROM users WHERE user_id = ?");
  if ($stmt === false) {
    header("Location: profile_page.php?status=error&message=" . urlencode('Database error.'));
    exit();
  }
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $stmt->bind_result($hash);
  if ($stmt->fetch() && password_verify($current, $hash)) {
    $stmt->close();
    $newHash = password_hash($new, PASSWORD_DEFAULT);
    $upd = $conn->prepare("UPDATE users SET pass = ? WHERE user_id = ?");
    if ($upd === false) {
      header("Location: profile_page.php?status=error&message=" . urlencode('Database error.'));
      exit();
    }
    $upd->bind_param("si", $newHash, $userId);
    if ($upd->execute()) {
      header("Location: profile_page.php?status=success&message=" . urlencode('Password updated.'));
    } else {
      header("Location: profile_page.php?status=error&message=" . urlencode('Could not update password.'));
    }
    $upd->close();
  } else {
    header("Location: profile_page.php?status=error&message=" . urlencode('Current password is incorrect.'));
  }
  exit();
}

header("Location: profile_page.php");
exit();
?>




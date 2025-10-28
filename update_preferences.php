<?php
session_start();
require_once "config/config.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'];
  $notifyEmail = isset($_POST['notify_email']) ? 1 : 0;
  $notifySms = isset($_POST['notify_sms']) ? 1 : 0;
  $language = $_POST['language'] ?? 'en';
  $timezone = $_POST['timezone'] ?? 'Asia/Manila';
  $timeWindow = $_POST['time_window'] ?? 'any';

  // Persist to session immediately for UX
  $_SESSION['pref_notify_email'] = $notifyEmail;
  $_SESSION['pref_notify_sms'] = $notifySms;
  $_SESSION['pref_language'] = $language;
  $_SESSION['pref_timezone'] = $timezone;
  $_SESSION['pref_time_window'] = $timeWindow;

  // Optional DB storage if table exists
  $sql = "UPDATE users SET pref_notify_email=?, pref_notify_sms=?, pref_language=?, pref_timezone=?, pref_time_window=? WHERE user_id=?";
  $stmt = $conn->prepare($sql);
  if ($stmt === false) {
    // Try to add columns then retry
    @$conn->query("ALTER TABLE users ADD COLUMN pref_notify_email TINYINT(1) DEFAULT 1");
    @$conn->query("ALTER TABLE users ADD COLUMN pref_notify_sms TINYINT(1) DEFAULT 0");
    @$conn->query("ALTER TABLE users ADD COLUMN pref_language VARCHAR(10) DEFAULT 'en'");
    @$conn->query("ALTER TABLE users ADD COLUMN pref_timezone VARCHAR(64) DEFAULT 'Asia/Manila'");
    @$conn->query("ALTER TABLE users ADD COLUMN pref_time_window VARCHAR(16) DEFAULT 'any'");
    $stmt = $conn->prepare($sql);
  }

  if ($stmt) {
    $stmt->bind_param("iisssi", $notifyEmail, $notifySms, $language, $timezone, $timeWindow, $userId);
    $stmt->execute();
    $stmt->close();
  }

  header("Location: profile_page.php?status=success&message=" . urlencode('Preferences saved.'));
  exit();
}

header("Location: profile_page.php");
exit();
?>




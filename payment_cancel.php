<?php
session_start();
unset($_SESSION['payment_verified'], $_SESSION['payment_initiated']);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = rtrim($scheme . '://' . $host . $base, '/');
header('Location: ' . $baseUrl . '/schedule.php?payment_cancelled=1');
exit;



<?php
date_default_timezone_set('Asia/Manila');

// Load local .env file if it exists (Vercel automatically provides these)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim(trim($value), '"\''));
    }
}

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db = getenv('DB_NAME');

$conn = mysqli_init();

if ($port == 4000 || getenv('DB_USE_SSL') === 'true') {
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
}
if (!$conn->real_connect($host, $user, $pass, $db, $port)) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8mb4");
?>
<?php
// Set the current working directory to the root folder so relative paths work (like 'config/config.php')
chdir(__DIR__ . '/../');

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Map the root URL to index.php
if ($uri === '/' || $uri === '') {
    $uri = '/index.php';
}

$file = __DIR__ . '/..' . $uri;

if (file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
} else {
    http_response_code(404);
    echo "404 Not Found";
}

<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if ($uri === '/' || $uri === '') {
    $uri = '/index.php';
}

$file = realpath(__DIR__ . '/..' . $uri);

if ($file && file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    // Emulate XAMPP behavior by setting the working directory to the target file's folder
    chdir(dirname($file));
    $_SERVER['SCRIPT_FILENAME'] = $file;
    
    require $file;
} else {
    http_response_code(404);
    echo "404 Not Found";
}

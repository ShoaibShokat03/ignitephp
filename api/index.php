<?php
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

use Ignite\Core\Route;
use Dotenv\Dotenv;

// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../config/');
$dotenv->load();

// Load CORS config
$corsConfig = require __DIR__ . '/../config/cors.php';

// Get the origin of the request
$requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($corsConfig['allow_all_origins']) {
    header("Access-Control-Allow-Origin: *");
} else {
    if (in_array($requestOrigin, $corsConfig['allowed_origins'])) {
        header("Access-Control-Allow-Origin: $requestOrigin");
    } else {
        // Origin not allowed: optional, you can handle rejection here if you want
        header('HTTP/1.1 403 Forbidden');
        exit('Origin not allowed');
    }
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/../routes/api.php';

header('Content-Type: application/json');

$response = Route::dispatch();
if ($response !== null) {
    echo json_encode($response);
}

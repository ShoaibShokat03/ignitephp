<?php
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

use Ignite\Core\Route;
use Dotenv\Dotenv;
// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../config/');
$dotenv->load();

require __DIR__ . '/../routes/api.php';

header('Content-Type: application/json');

$response = Route::dispatch();
if ($response !== null) {
    echo json_encode($response);
}

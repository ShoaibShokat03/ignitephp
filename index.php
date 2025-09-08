<?php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

use Ignite\Core\Route;
use Dotenv\Dotenv;

try {
    // Load environment variables
    $dotenv = Dotenv::createImmutable(__DIR__ . '/config/');
    $dotenv->load();

    // -------- CORS CONFIG ----------
    $corsConfig = require __DIR__ . '/config/cors.php';
    $requestOrigin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if ($corsConfig['allow_all_origins']) {
        header("Access-Control-Allow-Origin: *");
    } else {
        if (in_array($requestOrigin, $corsConfig['allowed_origins'])) {
            header("Access-Control-Allow-Origin: $requestOrigin");
        } else {
            header('HTTP/1.1 403 Forbidden');
            exit(json_encode(['status' => 'error', 'message' => 'Origin not allowed']));
        }
    }

    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // -------- SECURITY MIDDLEWARE ----------

    // 🛡 Secure Headers
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: no-referrer-when-downgrade");
    header("Content-Security-Policy: default-src 'none'; frame-ancestors 'none'; base-uri 'none'; form-action 'self'");

    // 🛡 Block dangerous HTTP methods
    $badMethods = ['TRACE', 'CONNECT', 'PATCH'];
    if (in_array($_SERVER['REQUEST_METHOD'], $badMethods)) {
        http_response_code(405);
        exit(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
    }

    // 🛡 Rate Limiting
    function rateLimit($ip, $limit = 100, $seconds = 60) {
        $dir = __DIR__ . "/storage/ratelimit";
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $safeIp = preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $ip);
        $file = "$dir/{$safeIp}.json";
        $now = time();

        if (!file_exists($file)) {
            file_put_contents($file, json_encode(['count' => 1, 'start' => $now]));
            return true;
        }

        $data = json_decode(file_get_contents($file), true);

        if ($now - $data['start'] < $seconds) {
            if ($data['count'] >= $limit) {
                http_response_code(429);
                echo json_encode(['status' => 'error', 'message' => 'Too many requests, slow down!']);
                exit;
            } else {
                $data['count']++;
            }
        } else {
            $data = ['count' => 1, 'start' => $now];
        }

        file_put_contents($file, json_encode($data));
        return true;
    }

    rateLimit($_SERVER['REMOTE_ADDR'] ?? 'unknown');

    // 🛡 Sanitize Input (basic filter)
    foreach ($_GET as $k => $v) {
        $_GET[$k] = is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v;
    }
    foreach ($_POST as $k => $v) {
        $_POST[$k] = is_string($v) ? htmlspecialchars($v, ENT_QUOTES, 'UTF-8') : $v;
    }

    // 🛡 Block suspicious headers
    $forbiddenHeaders = ['X-Forwarded-For', 'Forwarded', 'Proxy-Authorization'];
    foreach ($forbiddenHeaders as $h) {
        if (isset($_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $h))])) {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Suspicious request']));
        }
    }

    // -------- API DISPATCH ----------
    require __DIR__ . '/routes/api.php';

    header('Content-Type: application/json; charset=UTF-8');

    $response = Route::dispatch();
    if ($response !== null) {
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal Server Error',
        'error_detail' => $th->getMessage(),
        'file' => $th->getFile(),
        'line' => $th->getLine()
    ]);
}

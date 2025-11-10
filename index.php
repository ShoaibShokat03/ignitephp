<?php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

use Ignitephp\Core\Route;
use Dotenv\Dotenv;
use App\Core\ModuleLoader;
use App\Core\RouteConflictChecker;
use App\Core\PublicFileServer;

try {
    // -------- SECURITY: Prevent direct file access --------
    // Block direct access to PHP files except index.php
    $requestedFile = $_SERVER['SCRIPT_NAME'] ?? '';
    $requestedPath = $_SERVER['REQUEST_URI'] ?? '/';

    // Extract the actual file being requested
    $pathInfo = pathinfo(parse_url($requestedPath, PHP_URL_PATH));
    $requestedExtension = $pathInfo['extension'] ?? '';
    $requestedBasename = $pathInfo['basename'] ?? '';


    // -------- PUBLIC FILE SERVING --------
    // Check if this is a request for a public file
    // Files in public/ folder are accessible directly without /public prefix
    // Example: public/me.jfif -> /me.jfif
    // Example: public/images/logo.png -> /images/logo.png
    $publicFileServer = new PublicFileServer(__DIR__ . '/public');
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

    // Only serve public files for GET requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Try to serve the file
        if ($publicFileServer->isPublicFileRequest($requestUri)) {
            $publicFileServer->serveFile($requestUri);
            exit;
        }

        // If it looks like a file request but wasn't found, check if it's a routing issue
        $path = parse_url($requestUri, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // If it has a file extension, it's likely a file request
        if (!empty($extension)) {
            // Check if file exists directly in public folder
            $publicPath = __DIR__ . '/public';
            $normalizedPath = ltrim($path, '/');

            // Remove /api/ prefix if present
            if (strpos($normalizedPath, 'api/') === 0) {
                $normalizedPath = substr($normalizedPath, 4);
            }

            $testFilePath = $publicPath . '/' . $normalizedPath;
            if (file_exists($testFilePath) && is_file($testFilePath)) {
                // File exists but path resolution failed - serve it directly
                $publicFileServer->serveFile($requestUri);
                exit;
            }
        }
    }

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
    header("Content-Security-Policy: default-src 'self'; frame-ancestors 'none'; base-uri 'none'");

    // 🛡 Block dangerous HTTP methods
    $badMethods = ['TRACE', 'CONNECT', 'PATCH'];
    if (in_array($_SERVER['REQUEST_METHOD'], $badMethods)) {
        http_response_code(405);
        exit(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
    }

    // 🛡 Rate Limiting
    function rateLimit($ip, $limit = 100, $seconds = 60)
    {
        $dir = __DIR__ . "/app/storage/ratelimit";
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

    // -------- ROUTE LOADING ----------
    // Initialize router first
    Route::init();

    // Load simple routes first (if routes/api.php exists)
    $routesFile = __DIR__ . '/routes/api.php';
    if (file_exists($routesFile)) {
        try {
            // Set source for api.php routes before loading
            Route::setSource('api.php');
            require $routesFile;
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Error loading routes file',
                'error_detail' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            exit;
        }
    }

    // Then load modular routes
    try {
        $moduleLoader = new ModuleLoader();
        $moduleLoader->loadModules();
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error loading modules',
            'error_detail' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        exit;
    }

    // -------- ROUTE CONFLICT CHECK ----------
    try {
        $router = Route::getRouter();
        if ($router !== null) {
            $conflictChecker = new RouteConflictChecker($router);
            $conflicts = $conflictChecker->checkConflicts();

            if ($conflictChecker->hasConflicts()) {
                // Log conflicts to error log
                error_log("ROUTE CONFLICTS DETECTED:\n" . $conflictChecker->getConflictReport());

                // Always show conflicts when detected (api.php vs module routes)
                http_response_code(500);
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Route conflicts detected between api.php and module routes',
                    'conflicts' => $conflicts,
                    'conflict_report' => $conflictChecker->getConflictReport(),
                    'note' => 'Please remove duplicate routes from either api.php or the conflicting module(s)'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                exit;
            }
        }
    } catch (\Throwable $e) {
        // Log conflict check error but don't block execution
        error_log('Route conflict check error: ' . $e->getMessage());
    }

    header('Content-Type: application/json; charset=UTF-8');

    // Dispatch route with error handling
    try {
        $response = Route::dispatch();
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Route execution error',
            'error_detail' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        exit;
    }
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

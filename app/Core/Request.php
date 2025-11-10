<?php

namespace Ignitephp\Core;

class Request
{
    protected static $method;
    protected static $headers;
    protected static $body;
    protected static $queryParams;
    protected static $parsedBody;
    protected static $files;
    protected static $allInput;

    protected static $get;
    protected static $post;
    protected static $put;
    protected static $delete;
    protected static $patch;

    // Initialize static properties on first use
    protected static function init()
    {
        if (self::$method === null) {
            self::$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            self::$headers = self::getAllHeaders();
            self::$queryParams = $_GET ?? [];
            self::$get = $_GET ?? [];
            self::$post = $_POST ?? [];
            self::$files = $_FILES ?? [];
            self::$body = file_get_contents('php://input');
            
            // Parse body (needs method to be set first)
            self::$parsedBody = self::parseBody();
            
            // Parse PUT, DELETE, PATCH from body
            self::$put = self::parseMethodBody('PUT');
            self::$delete = self::parseMethodBody('DELETE');
            self::$patch = self::parseMethodBody('PATCH');
            
            // Merge all input sources
            self::$allInput = array_merge(
                self::$get,
                self::$post,
                is_array(self::$parsedBody) ? self::$parsedBody : []
            );
        }
    }

    /**
     * Parse body for PUT, DELETE, PATCH methods
     */
    protected static function parseMethodBody(string $method): array
    {
        if (self::$method !== $method) {
            return [];
        }
        
        if (is_array(self::$parsedBody)) {
            return self::$parsedBody;
        }
        
        // Try to parse as form data
        if (!empty(self::$body)) {
            parse_str(self::$body, $parsed);
            return is_array($parsed) ? $parsed : [];
        }
        
        return [];
    }

    // Get HTTP method
    public static function getMethod(): string
    {
        self::init();
        return self::$method;
    }

    /**
     * Check if request method matches
     */
    public static function isMethod(string $method): bool
    {
        return strtoupper(self::getMethod()) === strtoupper($method);
    }

    /**
     * Check if request is GET
     */
    public static function isGet(): bool
    {
        return self::isMethod('GET');
    }

    /**
     * Check if request is POST
     */
    public static function isPost(): bool
    {
        return self::isMethod('POST');
    }

    /**
     * Check if request is PUT
     */
    public static function isPut(): bool
    {
        return self::isMethod('PUT');
    }

    /**
     * Check if request is DELETE
     */
    public static function isDelete(): bool
    {
        return self::isMethod('DELETE');
    }

    /**
     * Check if request is PATCH
     */
    public static function isPatch(): bool
    {
        return self::isMethod('PATCH');
    }

    // Get all headers (case-insensitive keys)
    public static function getHeaders(): array
    {
        self::init();
        return self::$headers;
    }

    // Get specific header value (case-insensitive)
    public static function getHeader(string $name): ?string
    {
        self::init();
        $name = strtolower($name);
        foreach (self::$headers as $key => $value) {
            if (strtolower($key) === $name) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Get query parameter with null check
     */
    public static function get(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$get;
        }
        return self::$get[$key] ?? $default;
    }

    /**
     * Get POST data with null check
     */
    public static function post(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$post;
        }
        return self::$post[$key] ?? $default;
    }

    /**
     * Get PUT data with null check
     */
    public static function put(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$put;
        }
        return self::$put[$key] ?? $default;
    }

    /**
     * Get DELETE data with null check
     */
    public static function delete(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$delete;
        }
        return self::$delete[$key] ?? $default;
    }

    /**
     * Get PATCH data with null check
     */
    public static function patch(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$patch;
        }
        return self::$patch[$key] ?? $default;
    }

    /**
     * Get file upload with null check
     */
    public static function files(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$files;
        }
        return self::$files[$key] ?? $default;
    }

    /**
     * Get input from any source (GET, POST, body) with null check
     */
    public static function input(?string $key = null, $default = null)
    {
        self::init();
        if ($key === null) {
            return self::$allInput;
        }
        return self::$allInput[$key] ?? $default;
    }

    /**
     * Get all input data
     */
    public static function all(): array
    {
        self::init();
        return self::$allInput;
    }

    /**
     * Check if input key exists
     */
    public static function has(string $key): bool
    {
        self::init();
        return isset(self::$allInput[$key]);
    }

    /**
     * Check if input key exists and is not empty
     */
    public static function filled(string $key): bool
    {
        self::init();
        return isset(self::$allInput[$key]) && !empty(self::$allInput[$key]);
    }

    /**
     * Check if input key is missing
     */
    public static function missing(string $key): bool
    {
        return !self::has($key);
    }

    /**
     * Get only specified keys from input
     */
    public static function only(array $keys): array
    {
        self::init();
        $result = [];
        foreach ($keys as $key) {
            if (isset(self::$allInput[$key])) {
                $result[$key] = self::$allInput[$key];
            }
        }
        return $result;
    }

    /**
     * Get all input except specified keys
     */
    public static function except(array $keys): array
    {
        self::init();
        $result = self::$allInput;
        foreach ($keys as $key) {
            unset($result[$key]);
        }
        return $result;
    }

    /**
     * Get query parameter with default
     */
    public static function query(?string $key = null, $default = null)
    {
        return self::get($key, $default);
    }

    // Get query parameters ($_GET)
    public static function getQueryParams(): array
    {
        self::init();
        return self::$queryParams;
    }

    // Get parsed body (json, form-data, or raw)
    public static function getBody()
    {
        self::init();
        return self::$parsedBody;
    }

    // Get raw input body
    public static function getRawBody(): string
    {
        self::init();
        return self::$body;
    }

    /**
     * Get only JSON data (null if not JSON or invalid)
     */
    public static function getJson()
    {
        self::init();
        $contentType = self::getHeader('Content-Type') ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            return is_array(self::$parsedBody) ? self::$parsedBody : null;
        }
        return null;
    }

    /**
     * Check if request is JSON
     */
    public static function isJson(): bool
    {
        $contentType = self::getHeader('Content-Type') ?? '';
        return stripos($contentType, 'application/json') !== false;
    }

    /**
     * Check if request is AJAX
     */
    public static function isAjax(): bool
    {
        $requestedWith = self::requestedWith();
        return $requestedWith !== null && strtolower($requestedWith) === 'xmlhttprequest';
    }

    /**
     * Get boolean value from input
     */
    public static function boolean(string $key, bool $default = false): bool
    {
        $value = self::input($key, $default);
        if (is_bool($value)) {
            return $value;
        }
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['1', 'true', 'on', 'yes'], true);
        }
        return (bool)$value;
    }

    /**
     * Get integer value from input
     */
    public static function integer(string $key, int $default = 0): int
    {
        $value = self::input($key, $default);
        return (int)$value;
    }

    /**
     * Get string value from input
     */
    public static function string(string $key, string $default = ''): string
    {
        $value = self::input($key, $default);
        return (string)$value;
    }

    /**
     * Get float value from input
     */
    public static function float(string $key, float $default = 0.0): float
    {
        $value = self::input($key, $default);
        return (float)$value;
    }

    /**
     * Get client IP address
     */
    public static function ip(): ?string
    {
        self::init();
        // Check for various IP headers (for proxies/load balancers)
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Handle comma-separated IPs (take first one)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    /**
     * Get user agent
     */
    public static function userAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    /**
     * Get referrer URL
     */
    public static function referrer(): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? $_SERVER['HTTP_REFERRER'] ?? null;
    }

    /**
     * Get referrer URL (alias for referrer)
     */
    public static function referer(): ?string
    {
        return self::referrer();
    }

    /**
     * Get Authorization header
     */
    public static function authorization(): ?string
    {
        return self::getHeader('Authorization');
    }

    /**
     * Get Bearer token from Authorization header
     * Returns null if not found or not a Bearer token
     */
    public static function bearerToken(): ?string
    {
        $auth = self::authorization();
        
        if ($auth === null) {
            return null;
        }

        // Check if it's a Bearer token
        if (stripos($auth, 'Bearer ') === 0) {
            return trim(substr($auth, 7));
        }

        return null;
    }

    /**
     * Get API key from Authorization header or X-API-Key header
     */
    public static function apiKey(): ?string
    {
        // Try X-API-Key header first
        $apiKey = self::getHeader('X-API-Key');
        if ($apiKey !== null) {
            return $apiKey;
        }

        // Try Authorization header (if not Bearer)
        $auth = self::authorization();
        if ($auth !== null && stripos($auth, 'Bearer ') !== 0) {
            return $auth;
        }

        return null;
    }

    /**
     * Get Accept header
     */
    public static function accept(): ?string
    {
        return self::getHeader('Accept');
    }

    /**
     * Get Accept-Language header
     */
    public static function acceptLanguage(): ?string
    {
        return self::getHeader('Accept-Language');
    }

    /**
     * Get Accept-Encoding header
     */
    public static function acceptEncoding(): ?string
    {
        return self::getHeader('Accept-Encoding');
    }

    /**
     * Get Content-Type header
     */
    public static function contentType(): ?string
    {
        return self::getHeader('Content-Type');
    }

    /**
     * Get Content-Length header
     */
    public static function contentLength(): ?string
    {
        return self::getHeader('Content-Length');
    }

    /**
     * Get Origin header
     */
    public static function origin(): ?string
    {
        return self::getHeader('Origin');
    }

    /**
     * Get Host header
     */
    public static function host(): ?string
    {
        return self::getHeader('Host') ?? $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? null;
    }

    /**
     * Get X-Requested-With header (for AJAX detection)
     */
    public static function requestedWith(): ?string
    {
        return self::getHeader('X-Requested-With');
    }

    /**
     * Get X-CSRF-Token header
     */
    public static function csrfToken(): ?string
    {
        return self::getHeader('X-CSRF-Token');
    }

    /**
     * Get X-Forwarded-For header
     */
    public static function forwardedFor(): ?string
    {
        return self::getHeader('X-Forwarded-For');
    }

    /**
     * Get X-Forwarded-Proto header
     */
    public static function forwardedProto(): ?string
    {
        return self::getHeader('X-Forwarded-Proto');
    }

    /**
     * Get X-Real-IP header
     */
    public static function realIp(): ?string
    {
        return self::getHeader('X-Real-IP');
    }

    /**
     * Check if request has Bearer token
     */
    public static function hasBearerToken(): bool
    {
        return self::bearerToken() !== null;
    }

    /**
     * Check if request has API key
     */
    public static function hasApiKey(): bool
    {
        return self::apiKey() !== null;
    }

    /**
     * Get all common headers as an associative array
     */
    public static function getCommonHeaders(): array
    {
        return [
            'authorization' => self::authorization(),
            'bearer_token' => self::bearerToken(),
            'api_key' => self::apiKey(),
            'referrer' => self::referrer(),
            'user_agent' => self::userAgent(),
            'accept' => self::accept(),
            'accept_language' => self::acceptLanguage(),
            'accept_encoding' => self::acceptEncoding(),
            'content_type' => self::contentType(),
            'content_length' => self::contentLength(),
            'origin' => self::origin(),
            'host' => self::host(),
            'x_requested_with' => self::requestedWith(),
            'x_csrf_token' => self::csrfToken(),
            'x_forwarded_for' => self::forwardedFor(),
            'x_forwarded_proto' => self::forwardedProto(),
            'x_real_ip' => self::realIp(),
        ];
    }

    /**
     * Get full URL
     */
    public static function url(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return $protocol . $host . $uri;
    }

    /**
     * Get request path (without query string)
     */
    public static function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        return $path ?: '/';
    }

    /**
     * Get request URI
     */
    public static function uri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    /**
     * Get base URL
     */
    public static function baseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        return $protocol . $host;
    }

    // Parse input body based on Content-Type
    protected static function parseBody()
    {
        $contentType = self::getHeader('Content-Type') ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $decoded = json_decode(self::$body, true);
            return $decoded !== null ? $decoded : [];
        }

        if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
            parse_str(self::$body, $parsed);
            return is_array($parsed) ? $parsed : [];
        }

        if (stripos($contentType, 'multipart/form-data') !== false) {
            // For multipart form data, return POST data
            return self::$post ?? [];
        }

        // For PUT/PATCH/DELETE, try to parse as form data
        if (in_array(self::$method, ['PUT', 'PATCH', 'DELETE']) && !empty(self::$body)) {
            parse_str(self::$body, $parsed);
            return is_array($parsed) ? $parsed : [];
        }

        // For other content types, return empty array (not raw body)
        return [];
    }

    // Helper to get all headers (compatible with different servers)
    protected static function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            return $headers !== false ? $headers : [];
        }

        // Fallback for servers without getallheaders()
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}

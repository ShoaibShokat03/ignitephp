<?php

namespace Ignite\Core;

class Request
{
    protected static $method;
    protected static $headers;
    protected static $body;
    protected static $queryParams;
    protected static $parsedBody;
    protected static $files;

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
            self::$put = $_PUT ?? [];
            self::$delete = $_DELETE ?? [];
            self::$patch = $_PATCH ?? [];
            self::$files = $_FILES ?? [];
            self::$body = file_get_contents('php://input');
            self::$parsedBody = self::parseBody();
        }
    }

    // Get HTTP method
    public static function getMethod(): string
    {
        self::init();
        return self::$method;
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

    public static function get($key=null)
    {
        self::init();
        if ($key !== null) {
            return self::$get[$key];
        }
        return self::$get;
    }

    public static function post($key)
    {
        self::init();
        if ($key !== null) {
            return self::$post[$key];
        }
        return self::$post;
    }

    public static function put() 
    {
        self::init();
        return self::$put;
    }

    public static function delete() 
    {
        self::init();
        return self::$delete;
    }

    public static function patch() 
    {
        self::init();
        return self::$patch;
    }

    public static function files($key=null) 
    {
        self::init();
        if ($key !== null) {
            return self::$files[$key];
        }
        return self::$files;
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

    // Get only JSON data (null if not JSON or invalid)
    public static function getJson()
    {
        self::init();
        $contentType = self::getHeader('Content-Type') ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            return self::$parsedBody;
        }
        return null;
    }

    // Parse input body based on Content-Type
    protected static function parseBody()
    {
        $contentType = self::getHeader('Content-Type') ?? '';

        if (stripos($contentType, 'application/json') !== false) {
            $decoded = json_decode(self::$body, true);
            return $decoded !== null ? $decoded : null;
        }

        if (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
            parse_str(self::$body, $parsed);
            return $parsed;
        }

        if (stripos($contentType, 'multipart/form-data') !== false) {
            // For multipart form data, use $_POST and $_FILES
            return ['post' => $_POST, 'files' => $_FILES];
        }

        // For other content types, return raw body
        return self::$body;
    }

    // Helper to get all headers (compatible with different servers)
    protected static function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
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

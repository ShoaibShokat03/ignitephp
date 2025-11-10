<?php

namespace Ignitephp\Core;

use Ignitephp\Core\Router;

class Route
{
    protected static ?Router $router = null;

    public static function init(): void
    {
        if (self::$router === null) {
            self::$router = new Router();
        }
    }

    public static function get(string $path, callable $handler): void
    {
        self::init();
        self::$router->add('GET', $path, $handler);
    }

    public static function post(string $path, callable $handler): void
    {
        self::init();
        self::$router->add('POST', $path, $handler);
    }

    public static function put(string $path, callable $handler): void
    {
        self::init();
        self::$router->add('PUT', $path, $handler);
    }

    public static function delete(string $path, callable $handler): void
    {
        self::init();
        self::$router->add('DELETE', $path, $handler);
    }

    public static function patch(string $path, callable $handler): void
    {
        self::init();
        self::$router->add('PATCH', $path, $handler);
    }

    /**
     * Set the source for route registration (used for conflict detection)
     */
    public static function setSource(string $source): void
    {
        self::init();
        Router::setSource($source);
    }

    /**
     * Get the router instance (for conflict checking)
     */
    public static function getRouter(): ?Router
    {
        self::init();
        return self::$router;
    }

    public static function dispatch()
    {
        try {
            self::init();
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            
            if (empty($uri)) {
                $uri = '/';
            }
            
            return self::$router->dispatch($method, $uri);
        } catch (\Throwable $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Route dispatch error',
                'error_detail' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
        }
    }
}


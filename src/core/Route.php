<?php

namespace Ignite\Core;

use Ignite\Core\Router;

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

    public static function dispatch()
    {
        self::init();
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return self::$router->dispatch($method, $uri); // ✅ Return it!
    }
}

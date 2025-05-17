<?php

namespace Ignite\Core;

class Router
{
    protected array $routes = [];

    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $this->convertPath($path),
            'handler' => $handler,
            'raw'     => $path
        ];
    }

    public function dispatch(string $method, string $uri)
    {
        // Remove base path
        $targetRoute = str_replace($_ENV['BASE_URL'].'/api/', '/', $uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) continue;

            $pattern = $route['path'];

            if (preg_match($pattern, $targetRoute, $matches)) {
                // Remove numeric keys
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['handler'], $params);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found', 'uri' => $targetRoute]);
        exit;
    }


    private function convertPath(string $path): string
    {
        // Convert route like /user/{id} to regex
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<\1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}

<?php

namespace Ignitephp\Core;

class Router
{
    protected array $routes = [];
    protected static string $currentSource = 'api.php';

    public function add(string $method, string $path, callable $handler, ?string $source = null): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $this->convertPath($path),
            'handler' => $handler,
            'raw'     => $path,
            'source'  => $source ?? self::$currentSource
        ];
    }

    /**
     * Set the current source for route registration
     */
    public static function setSource(string $source): void
    {
        self::$currentSource = $source;
    }

    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    private function br()
    {
        echo "</br>";
    }
    public function dispatch(string $method, string $uri)
    {
        try {
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
            // Remove base path
            $targetRoute = str_replace($basePath . "/api", '', $uri);
            
            // Normalize the route - remove trailing slash for matching
            $targetRoute = rtrim($targetRoute, '/');
            if (empty($targetRoute)) {
                $targetRoute = '/';
            }

            foreach ($this->routes as $route) {
                if ($route['method'] !== strtoupper($method)) continue;

                $pattern = $route['path'];

                if (preg_match($pattern, $targetRoute, $matches)) {
                    // Remove numeric keys
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    
                    // Execute handler with error handling
                    try {
                        $result = call_user_func_array($route['handler'], $params);
                        
                        // Ensure result is an array
                        if (!is_array($result)) {
                            return ['status' => 'success', 'data' => $result];
                        }
                        
                        return $result;
                    } catch (\Throwable $e) {
                        // Log error details for debugging
                        error_log('Route handler error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
                        
                        http_response_code(500);
                        return [
                            'status' => 'error',
                            'message' => 'Handler execution error',
                            'error_detail' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine()
                        ];
                    }
                }
            }

            // Route not found
            http_response_code(404);
            return [
                'status' => 'error',
                'message' => 'Route not found',
                'uri' => $targetRoute,
                'method' => $method,
                'available_routes' => $this->getAvailableRoutes($method)
            ];
        } catch (\Throwable $e) {
            http_response_code(500);
            return [
                'status' => 'error',
                'message' => 'Router dispatch error',
                'error_detail' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ];
        }
    }

    /**
     * Get available routes for debugging
     * Separates simple routes and module routes grouped by module
     */
    private function getAvailableRoutes(string $method): array
    {
        $simpleRoutes = [];
        $moduleRoutes = [];
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }
            
            $source = $route['source'] ?? 'api.php';
            
            // Check if it's a simple route (from api.php)
            if ($source === 'api.php') {
                $simpleRoutes[] = $route['raw'];
            } 
            // Check if it's a module route (source format: "module:ModuleName")
            elseif (strpos($source, 'module:') === 0) {
                $moduleName = substr($source, 7); // Remove "module:" prefix
                if (!isset($moduleRoutes[$moduleName])) {
                    $moduleRoutes[$moduleName] = [];
                }
                $moduleRoutes[$moduleName][] = $route['raw'];
            }
            // Fallback for any other source types
            else {
                if (!isset($moduleRoutes[$source])) {
                    $moduleRoutes[$source] = [];
                }
                $moduleRoutes[$source][] = $route['raw'];
            }
        }
        
        // Build structured response
        $result = [];
        
        if (!empty($simpleRoutes)) {
            $result['simple_routes'] = $simpleRoutes;
        }
        
        if (!empty($moduleRoutes)) {
            $result['module_routes'] = $moduleRoutes;
        }
        
        return $result;
    }


    private function convertPath(string $path): string
    {
        // Normalize path - remove trailing slash for matching
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }
        
        // Convert route like /user/{id} to regex
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<\1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}


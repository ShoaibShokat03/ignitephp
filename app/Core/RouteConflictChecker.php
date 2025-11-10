<?php

namespace App\Core;

use Ignitephp\Core\Router;

/**
 * Route Conflict Checker
 * Detects conflicts between routes in api.php and module routes
 */
class RouteConflictChecker
{
    protected Router $router;
    protected array $conflicts = [];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Check for route conflicts
     * 
     * @return array Array of conflicts found
     */
    public function checkConflicts(): array
    {
        $routes = $this->router->getRoutes();
        $this->conflicts = [];
        
        // Group routes by method and normalized path
        $routeMap = [];
        
        foreach ($routes as $index => $route) {
            $method = $route['method'];
            $rawPath = $this->normalizePath($route['raw']);
            $key = $method . ':' . $rawPath;
            
            if (!isset($routeMap[$key])) {
                $routeMap[$key] = [];
            }
            
            $routeMap[$key][] = [
                'index' => $index,
                'method' => $method,
                'path' => $route['raw'],
                'source' => $route['source'] ?? 'unknown',
                'normalized' => $rawPath
            ];
        }
        
        // Find conflicts (same method + path from different sources)
        // Specifically check for conflicts between api.php and module routes
        foreach ($routeMap as $key => $routeGroup) {
            if (count($routeGroup) > 1) {
                // Check if routes are from different sources
                $sources = array_unique(array_column($routeGroup, 'source'));
                
                if (count($sources) > 1) {
                    // Check if there's a conflict between api.php and a module
                    $hasApiRoute = false;
                    $hasModuleRoute = false;
                    $apiRouteInfo = null;
                    $moduleRouteInfo = null;
                    
                    foreach ($routeGroup as $routeInfo) {
                        if ($routeInfo['source'] === 'api.php') {
                            $hasApiRoute = true;
                            $apiRouteInfo = $routeInfo;
                        } elseif (strpos($routeInfo['source'], 'module:') === 0) {
                            $hasModuleRoute = true;
                            if ($moduleRouteInfo === null) {
                                $moduleRouteInfo = $routeInfo;
                            }
                        }
                    }
                    
                    // Only report conflicts between api.php and modules
                    if ($hasApiRoute && $hasModuleRoute) {
                        $this->conflicts[] = [
                            'method' => $routeGroup[0]['method'],
                            'path' => $routeGroup[0]['path'],
                            'normalized_path' => $routeGroup[0]['normalized'],
                            'api_route' => [
                                'source' => $apiRouteInfo['source'],
                                'path' => $apiRouteInfo['path']
                            ],
                            'module_route' => [
                                'source' => $moduleRouteInfo['source'],
                                'path' => $moduleRouteInfo['path']
                            ],
                            'all_sources' => array_map(function($route) {
                                return [
                                    'source' => $route['source'],
                                    'path' => $route['path']
                                ];
                            }, $routeGroup)
                        ];
                    }
                }
            }
        }
        
        return $this->conflicts;
    }

    /**
     * Normalize path for comparison (handles parameter variations)
     * Converts /user/{id} and /user/{userId} to the same normalized form
     */
    protected function normalizePath(string $path): string
    {
        // Normalize trailing slashes
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }
        
        // Replace all parameter placeholders with a generic placeholder
        // This allows /user/{id} and /user/{userId} to be detected as conflicts
        $normalized = preg_replace('#\{[^}]+\}#', '{param}', $path);
        
        return $normalized;
    }

    /**
     * Get formatted conflict report
     */
    public function getConflictReport(): string
    {
        if (empty($this->conflicts)) {
            return "✅ No route conflicts detected.\n";
        }
        
        $report = "⚠️  ROUTE CONFLICTS DETECTED:\n\n";
        $report .= "Found " . count($this->conflicts) . " conflict(s):\n\n";
        
        foreach ($this->conflicts as $index => $conflict) {
            $report .= sprintf(
                "%d. %s %s\n",
                $index + 1,
                $conflict['method'],
                $conflict['path']
            );
            
            $report .= "   ⚠️  CONFLICT: Same route defined in both api.php and module\n";
            $report .= sprintf(
                "   - api.php: %s %s\n",
                $conflict['method'],
                $conflict['api_route']['path']
            );
            $report .= sprintf(
                "   - %s: %s %s\n",
                $conflict['module_route']['source'],
                $conflict['method'],
                $conflict['module_route']['path']
            );
            $report .= "\n";
        }
        
        return $report;
    }

    /**
     * Check if there are any conflicts
     */
    public function hasConflicts(): bool
    {
        return !empty($this->conflicts);
    }

    /**
     * Get conflicts as array
     */
    public function getConflicts(): array
    {
        return $this->conflicts;
    }
}


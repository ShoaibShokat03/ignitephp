<?php

namespace App\Core;

use Ignitephp\Core\Route;

/**
 * Module Loader
 * Automatically discovers and loads all modules
 */
class ModuleLoader
{
    protected array $modules = [];
    protected string $modulesPath;

    public function __construct(string $modulesPath = null)
    {
        // Point to modules folder in root directory
        $this->modulesPath = $modulesPath ?? dirname(__DIR__, 2) . '/modules';
    }

    /**
     * Load all modules from the modules directory
     */
    public function loadModules(): void
    {
        if (!is_dir($this->modulesPath)) {
            return;
        }

        $directories = array_filter(glob($this->modulesPath . '/*'), 'is_dir');

        foreach ($directories as $moduleDir) {
            $moduleName = basename($moduleDir);
            $moduleFile = $moduleDir . '/Module.php';

            // Check if module file exists
            if (file_exists($moduleFile)) {
                $className = "App\\Modules\\{$moduleName}\\Module";
                
                if (class_exists($className)) {
                    $module = new $className();
                    if ($module instanceof Module) {
                        $this->modules[] = $module;
                        // Set source for conflict detection
                        Route::setSource("module:{$moduleName}");
                        $module->registerRoutes();
                    }
                }
            } else {
                // Auto-load module if routes.php exists (legacy support)
                $routesFile = $moduleDir . '/routes.php';
                if (file_exists($routesFile)) {
                    // Set source for conflict detection
                    Route::setSource("module:{$moduleName}");
                    require $routesFile;
                }
            }
        }
    }

    /**
     * Get all loaded modules
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * Manually register a module
     */
    public function registerModule(Module $module): void
    {
        $this->modules[] = $module;
        $module->registerRoutes();
    }
}


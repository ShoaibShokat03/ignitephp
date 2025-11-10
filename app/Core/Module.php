<?php

namespace App\Core;

use Ignitephp\Core\Route;

/**
 * Base Module Class
 * All modules should extend this class
 */
abstract class Module
{
    protected string $name;
    protected string $prefix;

    public function __construct(string $name, string $prefix = '')
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    /**
     * Register routes for this module
     * Override this method in your module to define routes
     */
    abstract public function registerRoutes(): void;

    /**
     * Get module name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get route prefix
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Helper method to prefix routes
     */
    protected function route(string $path): string
    {
        if (empty($this->prefix)) {
            return $path;
        }
        
        // If path is empty, just return the prefix
        if (empty($path)) {
            return $this->prefix;
        }
        
        // Combine prefix and path, ensuring no double slashes
        $prefix = rtrim($this->prefix, '/');
        $path = ltrim($path, '/');
        return $prefix . '/' . $path;
    }
}


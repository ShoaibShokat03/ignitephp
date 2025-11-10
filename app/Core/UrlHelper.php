<?php

namespace App\Core;

/**
 * URL Helper
 * Provides helper functions for generating URLs
 */
class UrlHelper
{
    protected static ?PublicFileServer $publicFileServer = null;

    /**
     * Get the base URL of the application
     */
    public static function baseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        
        // Remove /api if present in script name
        $scriptName = rtrim(str_replace('/api', '', $scriptName), '/');
        
        return $protocol . $host . $scriptName;
    }

    /**
     * Get URL for a public file
     * Example: UrlHelper::publicUrl('images/logo.png') returns '/images/logo.png'
     * The base URL is automatically handled by the browser
     */
    public static function publicUrl(string $path): string
    {
        $path = ltrim($path, '/');
        return '/' . $path;
    }

    /**
     * Get full URL for a public file
     * Example: UrlHelper::publicUrlFull('images/logo.png') returns 'http://domain.com/images/logo.png'
     */
    public static function publicUrlFull(string $path): string
    {
        if (self::$publicFileServer === null) {
            self::$publicFileServer = new PublicFileServer();
        }
        return self::$publicFileServer->getPublicUrl($path);
    }

    /**
     * Get the current request URL
     */
    public static function currentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return $protocol . $host . $uri;
    }
}


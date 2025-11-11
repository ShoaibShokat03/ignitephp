<?php

/**
 * Simple API Routes
 * 
 * This file allows you to create routes without using the modular approach.
 * Perfect for quick prototypes, simple APIs, or when you prefer traditional routing.
 * 
 * Usage:
 * Route::get('/path', function() { return ['data' => 'value']; });
 * Route::post('/path', function() { return ['data' => 'value']; });
 */

use App\Core\UrlHelper;
use Ignitephp\Core\Route;
use Ignitephp\Core\Request;

// ============================================
// Example Routes (Remove or modify as needed)
// ============================================

// Home/Welcome route
Route::get('/', function () {
    return [
        'status' => 'success',
        'message' => 'Welcome to IgnitePHP API',
        'version' => '1.3.0',
        'timestamp' => date('Y-m-d H:i:s')
    ];
});

// Get Image
Route::get('/image', function () {
    return [
        'status' => 'success',
        'message' => 'Image',
        'image' => UrlHelper::baseUrl() . '/public/me.jfif',
        'image_full' => UrlHelper::publicUrlFull('me.jfif'),
    ];
});

// Health check route
Route::get('/health', function () {
    return [
        'status' => 'healthy',
        'server' => 'running',
        'timestamp' => date('Y-m-d H:i:s')
    ];
});

// Example: Simple GET route
Route::get('/example', function () {
    return [
        'message' => 'This is a simple route example',
        'method' => Request::getMethod(),
        'data' => Request::getQueryParams()
    ];
});

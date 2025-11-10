<?php

namespace App\Modules\Users;

use App\Core\Module as BaseModule;
use App\Modules\Users\Controller;
use Ignitephp\Core\Route;

/**
 * Users Module
 */
class Module extends BaseModule
{
    public function __construct()
    {
        parent::__construct('Users', '/users');
    }

    public function registerRoutes(): void
    {
        $controller = new Controller();

        // Register routes
        Route::get('/users', [$controller, 'index']);
        Route::get('/users/{id}', [$controller, 'show']);
        Route::post('/users', [$controller, 'store']);
        Route::put('/users/{id}', [$controller, 'update']);
        Route::delete('/users/{id}', [$controller, 'destroy']);
    }
}
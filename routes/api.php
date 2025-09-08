<?php

use Ignite\Core\Fetch;
use Ignite\Core\Route;
use Ignite\Core\Request;

$projects = include __DIR__ . '/../config/projects.php';

Route::get("/hi", function () {

    return [
        'status' => 'success',
        'message' => 'Hello, World!'
    ];
});

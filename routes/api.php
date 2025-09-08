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

// Simple echo with query params
Route::get('/echo', function () {
    return [
        'status' => 'success',
        'method' => Request::getMethod(),
        'query'  => Request::get(),
        'headers'=> Request::getHeaders(),
    ];
});

// Path param example
Route::get('/users/{id}', function ($id) {
    return [
        'status' => 'success',
        'user'   => [
            'id' => $id,
            'name' => 'Demo User ' . $id,
        ],
    ];
});

// Create user (JSON or form-encoded)
Route::post('/users', function () {
    $json = Request::getJson();
    $body = $json !== null ? $json : Request::getBody();
    return [
        'status' => 'created',
        'received' => $body,
    ];
});

// Update user (PUT)
Route::put('/users/{id}', function ($id) {
    return [
        'status' => 'updated',
        'id' => $id,
        'received' => Request::getBody(),
        'raw' => Request::getRawBody(),
    ];
});

// Partial update (PATCH)
Route::patch('/users/{id}', function ($id) {
    return [
        'status' => 'patched',
        'id' => $id,
        'received' => Request::getBody(),
    ];
});

// Delete user
Route::delete('/users/{id}', function ($id) {
    return [
        'status' => 'deleted',
        'id' => $id,
    ];
});

// Headers demo
Route::get('/headers', function () {
    return [
        'status' => 'success',
        'headers' => Request::getHeaders(),
        'authorization' => Request::getHeader('Authorization'),
    ];
});

// File upload demo (multipart/form-data)
Route::post('/upload', function () {
    $files = Request::files();
    $summary = [];
    foreach ($files as $key => $file) {
        $summary[$key] = [
            'name' => $file['name'] ?? null,
            'type' => $file['type'] ?? null,
            'size' => $file['size'] ?? null,
            'error'=> $file['error'] ?? null,
        ];
    }
    return [
        'status' => 'success',
        'files' => $summary,
        'post'  => Request::post(null),
    ];
});
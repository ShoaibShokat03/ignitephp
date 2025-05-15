<?php

use Ignite\Core\Route;

Route::get('/hello', function () {
    return ['message' => 'Hello, World!'];
});

Route::get('/user/{id}', function ($id) {
    return ['user_id' => $id];
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'Test';
});

Route::post('/register', 'Authentication\User\CreateUserController');
Route::post('/login', 'Authentication\User\LoginController');

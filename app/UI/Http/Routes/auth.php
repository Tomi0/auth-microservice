<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', 'Authentication\User\CreateUserController');
Route::post('/login', 'Authentication\User\LoginController');

Route::post('/user/change-password', 'Authentication\User\ChangeUserPasswordController');
Route::post('/token-reset-password', 'Authentication\TokenResetPassword\GenerateTokenResetPasswordController');

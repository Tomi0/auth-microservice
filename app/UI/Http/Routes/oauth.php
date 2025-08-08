<?php

use App\UI\Http\Controllers\Authentication\AccessToken\GetAccessTokenController;
use App\UI\Http\Controllers\Authentication\User\AuthorizeUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', 'Authentication\User\CreateUserController');

Route::post('/authorize', AuthorizeUserController::class);
Route::post('/token', GetAccessTokenController::class);

Route::post('/user/change-password', 'Authentication\User\ChangeUserPasswordController');
Route::post('/token-reset-password', 'Authentication\TokenResetPassword\GenerateTokenResetPasswordController');

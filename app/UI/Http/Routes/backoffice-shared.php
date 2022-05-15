<?php

use Illuminate\Support\Facades\Route;

Route::post('/token-reset-password', 'Authentication\TokenResetPassword\GetTokenResetPasswordController');

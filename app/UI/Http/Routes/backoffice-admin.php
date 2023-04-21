<?php


use Illuminate\Support\Facades\Route;

Route::delete('/user/{user_id}', 'Authentication\User\DeleteUserController');
Route::get('/user/search', 'Authentication\User\SearchUserController');

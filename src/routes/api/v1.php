<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;

Route::post('/auth/signup', [UserController::class, 'signup']);
Route::post('/auth/login', [UserController::class, 'login']);

Route::get('/users', [UserController::class, 'list'])->middleware('auth:sanctum');
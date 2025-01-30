<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelOrderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/travel-orders', [TravelOrderController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/protected-route', [AuthController::class, 'protectedRoute']);
});

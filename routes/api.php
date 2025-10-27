<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SalesController;
use App\Http\Controllers\AuthController;

use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:api']);

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('sales', SalesController::class);
});

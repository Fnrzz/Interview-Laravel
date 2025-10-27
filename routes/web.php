<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('products', ProductController::class)->names([
    'index' => 'dashboard.products.index',
    'create' => 'dashboard.products.create',
    'store' => 'dashboard.products.store',
    'edit' => 'dashboard.products.edit',
    'update' => 'dashboard.products.update',
    'destroy' => 'dashboard.products.destroy',
]);
Route::get('/sales/create', [TransactionController::class, 'create'])->name('dashboard.sales.create');
Route::post('/sales', [TransactionController::class, 'store'])->name('dashboard.sales.store');

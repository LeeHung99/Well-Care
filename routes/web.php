<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
});
Route::get('login', [UserController::class, 'send']);
Route::post('/send-sms', [UserController::class, 'send']);

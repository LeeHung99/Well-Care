<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\AdminPostsController;

Route::get('login', [AdminController::class, 'loginAdmin'])->name('login');
Route::post('login_verify', [AdminController::class, 'loginVerify'])->name('loginVerify');
// Route::get('createAdminUser', [AdminController::class, 'createAdminUser']);

Route::get('order_view', [CheckOutController::class, 'test_view_checkout'])->name('order_view');
Route::post('order_test', [CheckOutController::class, 'store'])->name('order_test');
Route::get('vnpay_return', [CheckOutController::class, 'vnpayReturn']);


Route::get('/exit', [AdminController::class, 'exit']);
Route::middleware(['auth', CheckAdmin::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/post', [AdminPostsController::class, 'index']);
    Route::get('/createpost', [AdminPostsController::class, 'createpost']);
    Route::post('/storepost', [AdminPostsController::class, 'storepost']);
    Route::get('/editpost{id_post}', [AdminPostsController::class, 'editpost']);
    Route::post('/updatepost{id_post}', [AdminPostsController::class, 'updatepost']);
    Route::post('/destroypost{id_post}', [AdminPostsController::class, 'destroypost']);
});

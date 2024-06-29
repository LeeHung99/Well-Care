<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CheckAdmin;

Route::get('login', [AdminController::class, 'loginAdmin'])->name('login');
Route::post('login_verify', [AdminController::class, 'loginVerify'])->name('loginVerify');

// Route::get('createAdminUser', [AdminController::class, 'createAdminUser']);

Route::middleware(['auth', CheckAdmin::class])->prefix('admin')->group(function()  {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/post', [AdminPostsController::class, 'index']);
    Route::get('/createpost', [AdminPostsController::class, 'createpost']);
    Route::post('/storepost', [AdminPostsController::class, 'storepost']);
    Route::get('/editpost{id_post}', [AdminPostsController::class, 'editpost']);
    Route::post('/updatepost{id_post}', [AdminPostsController::class, 'updatepost']);
    Route::post('/destroypost{id_post}', [AdminPostsController::class, 'destroypost']);
});



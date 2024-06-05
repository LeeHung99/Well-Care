<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/cate') -> group(function () {
    Route::get('/', [ProductController::class, 'cate']);
});

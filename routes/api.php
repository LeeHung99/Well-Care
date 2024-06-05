<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/cate') -> group(function () {
    Route::get('/', [ProductController::class, 'cate']);
});
Route::prefix('/product') -> group(function () {
    Route::get('/', [ProductController::class, 'product']);
    Route::get('{id_product}', [ProductController::class, ['productDetail']]);
    Route::get('{id_category}', [ProductController::class, ['productCate']]);
});

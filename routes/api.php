<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;


Route::prefix('/cate')->group(function () {
    Route::get('/', [ProductController::class, 'cate']);
});
Route::prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'product']);
    Route::get('{id_product}', [ProductController::class, 'productDetail']);
});
Route::get('sale', [ProductController::class, 'productSale']);
Route::get('hot', [ProductController::class, 'productHot']);
Route::get('sold', [ProductController::class, 'productSold']);
Route::get('voucher', [ProductController::class, 'voucher']);
Route::get('sick', [ProductController::class, 'sick']);
Route::get('productsick{id_sick}', [ProductController::class, 'productsick']);
Route::get('/productcate{id_category}', [ProductController::class, 'productCate']);
Route::get('bill', [ProductController::class, 'bill']);
Route::get('billdetail{id_bill}', [ProductController::class, 'billdetail']);
Route::get('login', [UserController::class, 'login']);
<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/cate')->group(function () {
    Route::get('/', [ProductController::class, 'cate']);
});
Route::prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'product']);
    Route::get('{id_product}', [ProductController::class, 'productDetail']);
});



//Huy
Route::get('sale', [ProductController::class, 'productSale']);
Route::get('hot', [ProductController::class, 'productHot']);
Route::get('sold', [ProductController::class, 'productSold']);
Route::get('voucher', [ProductController::class, 'voucher']);




Route::get('/productcate{id_category}', [ProductController::class, 'productCate']);

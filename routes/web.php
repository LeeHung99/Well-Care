<?php

use App\Http\Controllers\AdminBannerController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLogoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\AdminVoucherController;

Route::get('login', [AdminController::class, 'loginAdmin'])->name('login');
Route::post('login_verify', [AdminController::class, 'loginVerify'])->name('loginVerify');
// Route::get('createAdminUser', [AdminController::class, 'createAdminUser']);

Route::get('order_view', [CheckOutController::class, 'test_view_checkout'])->name('order_view');
Route::post('/order_test', [CheckOutController::class, 'store'])->name('order_test');
Route::get('vnpay_return', [CheckOutController::class, 'vnpayReturn'])->name('vnpay_return');
Route::get('/momo-callback', [CheckOutController::class, 'momoCallback'])->name('momo.callback');

Route::get('/exit', [AdminController::class, 'exit']);
Route::middleware(['auth', CheckAdmin::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/post', [AdminPostsController::class, 'index']);
    Route::get('/createpost', [AdminPostsController::class, 'createpost']);
    Route::post('/storepost', [AdminPostsController::class, 'storepost']);
    Route::get('/editpost{id_post}', [AdminPostsController::class, 'editpost']);
    Route::post('/updatepost{id_post}', [AdminPostsController::class, 'updatepost']);
    Route::post('/destroypost{id_post}', [AdminPostsController::class, 'destroypost']);

    Route::get('/banner', [AdminBannerController::class, 'index']);
    Route::get('/createbanner', [AdminBannerController::class, 'createbanner']);
    Route::post('/storebanner', [AdminBannerController::class, 'storebanner']);
    Route::get('/editbanner{id_image_banner}', [AdminBannerController::class, 'editbanner']);
    Route::post('/updatebanner{id_image_banner}', [AdminBannerController::class, 'updatebanner']);
    Route::post('/destroybanner{id_image_banner}', [AdminBannerController::class, 'destroybanner']);

    Route::get('/voucher', [AdminVoucherController::class, 'index']);
    Route::get('/createvoucher', [AdminVoucherController::class, 'createvoucher']);
    Route::post('/storevoucher', [AdminVoucherController::class, 'storevoucher']);
    Route::get('/editvoucher{id_voucher}', [AdminVoucherController::class, 'editvoucher']);
    Route::post('/updatevoucher{id_voucher}', [AdminVoucherController::class, 'updatevoucher']);
    Route::post('/destroyvoucher{id_voucher}', [AdminVoucherController::class, 'destroyvoucher']);

    Route::get('/logo', [AdminLogoController::class, 'index']);
    Route::get('/editlogo{id_logo}', [AdminLogoController::class, 'editlogo']);
    Route::post('/updatelogo{id_logo}', [AdminLogoController::class, 'updatelogo']);
});

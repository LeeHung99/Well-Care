<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\UploadController;
use App\Http\Middleware\CheckEditorPostRole;
use App\Http\Middleware\CheckEditorRole;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
// use App\Http\Controllers\UploadController;
use App\Http\Controllers\AdminKhController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\AdminBillController;
use App\Http\Controllers\AdminLogoController;
use App\Http\Controllers\AdminPostsController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminBannerController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminVoucherController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\AdminObjectController;
use App\Http\Controllers\AdminSeCategoryController;
use App\Http\Controllers\AdminThirdCategoryController;
use App\Http\Controllers\SickController;

Route::get('login', [AdminController::class, 'loginAdmin'])->name('login');
Route::post('login_verify', [AdminController::class, 'loginVerify'])->name('loginVerify');
// Route::get('createAdminUser', [AdminController::class, 'createAdminUser']);


Route::get('order_view', [CheckOutController::class, 'test_view_checkout'])->name('order_view');
Route::get('order_view', [CheckOutController::class, 'test_view_checkout'])->name('order_view');
// Route::post('/order_test', [CheckOutController::class, 'store'])->name('order_test');
Route::middleware('api')->post('/order_test', [CheckOutController::class, 'store']);
Route::middleware('api')->post('/order_view', [CheckOutController::class, 'test_view_checkout']);
Route::get('vnpay_return', [CheckOutController::class, 'vnpayReturn'])->name('vnpay_return');
Route::get('/momo-callback', [CheckOutController::class, 'momoCallback'])->name('momo.callback');

Route::get('/exit', [AdminController::class, 'exit']);
Route::middleware(['auth', CheckAdmin::class])->prefix('admin')->group(function () {    
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/update-data', [AdminController::class, 'updateData']);
    Route::get('/update-revenue-data', [AdminController::class, 'updateRevenueData']);

    /**
     * Post
     */
    // Route::get('/post', [AdminPostsController::class, 'index']);
    // Route::get('/createpost', [AdminPostsController::class, 'createpost']);
    // Route::post('/storepost', [AdminPostsController::class, 'storepost']);
    // Route::get('/editpost{id_post}', [AdminPostsController::class, 'editpost']);
    // Route::post('/updatepost{id_post}', [AdminPostsController::class, 'updatepost']);
    // Route::post('/destroypost{id_post}', [AdminPostsController::class, 'destroypost']);

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

    /**
     * 
     * Category
     * 
     */
    // Route::get('/category', [AdminCategoryController::class, 'index']);
    // Route::get('/createcategory', [AdminCategoryController::class, 'createcategory']);
    // Route::post('/storecategory', [AdminCategoryController::class, 'storecategory']);
    // Route::get('/editcategory{id_category}', [AdminCategoryController::class, 'editcategory']);
    // Route::post('/updatecategory{id_category}', [AdminCategoryController::class, 'updatecategory']);
    // Route::post('/destroycategory{id_category}', [AdminCategoryController::class, 'destroycategory']);

    // Route::get('/secategory', [AdminSeCategoryController::class, 'index']);
    // Route::get('/createsecategory', [AdminSeCategoryController::class, 'createsecategory']);
    // Route::post('/storesecategory', [AdminSeCategoryController::class, 'storesecategory']);
    // Route::get('/editsecategory{id_se_category}', [AdminSeCategoryController::class, 'editsecategory']);
    // Route::post('/updatesecategory{id_se_category}', [AdminSeCategoryController::class, 'updatesecategory']);
    // Route::post('/destroysecategory{id_se_category}', [AdminSeCategoryController::class, 'destroysecategory']);

    // Route::get('/thirdcategory', [AdminThirdCategoryController::class, 'index']);
    // Route::get('/createthirdcategory', [AdminThirdCategoryController::class, 'createthirdcategory']);
    // Route::post('/storethirdcategory', [AdminThirdCategoryController::class, 'storethirdcategory']);
    // Route::get('/editthirdcategory{id_category}', [AdminThirdCategoryController::class, 'editthirdcategory']);
    // Route::post('/updatethirdcategory{id_category}', [AdminThirdCategoryController::class, 'updatethirdcategory']);
    // Route::post('/destroythirdcategory{id_category}', [AdminThirdCategoryController::class, 'destroythirdcategory']);
    /**
     * 
     * Category
     * 
     */




    /**
     * 
     * Product
     */
    // Route::get('/product', [AdminProductController::class, 'index'])->name('product');
    // Route::get('/editproduct{id}', [AdminProductController::class, 'edit'])->name('editproduct');
    // Route::get('/storeproduct', [AdminProductController::class, 'storeView'])->name('storeproduct');
    // Route::post('/storeproduct', [AdminProductController::class, 'store']);
    // Route::post('/updateproduct{id}', [AdminProductController::class, 'updateproduct'])->name('updateproduct');
    // Route::get('/destroyproduct{id}', [AdminProductController::class, 'destroy'])->name('destroyproduct');
    // Route::delete('delete-image/{id}', [ImageController::class, 'deleteImage'])->name('delete.image');
    /**
     * 
     * Product
     */


    Route::get('/bill', [AdminBillController::class, 'index']);
    Route::get('/billdetail{id_bill}{id_user}', [AdminBillController::class, 'billdetail']);
    Route::post('/storebill', [AdminBillController::class, 'storebill']);
    Route::get('/editbill{id_bill}', [AdminBillController::class, 'editbill']);
    Route::post('/updatebill{id_bill}', [AdminBillController::class, 'updatebill']);
    Route::post('/destroybill{id_bill}', [AdminBillController::class, 'destroybill']);

    /**
     * User
     */
    // Route::get('/users', [AdminUsersController::class, 'index']);
    // Route::get('/createusers', [AdminUsersController::class, 'createusers']);
    // Route::post('/storeusers', [AdminUsersController::class, 'storeusers']);
    // Route::get('/editusers{id_user}', [AdminUsersController::class, 'editusers']);
    // Route::post('/updateusers{id_user}', [AdminUsersController::class, 'updateusers']);
    // Route::post('/destroyusers{id_user}', [AdminUsersController::class, 'destroyusers']);
    // Route::get('/kh', [AdminKhController::class, 'index']);


    /**
     * Sick & Object
     */
    // Route::get('/sick', [SickController::class, 'index'])->name('sick');
    // Route::get('/storeView', [SickController::class, 'storeView'])->name('storeView');
    // Route::post('/storesick', [SickController::class, 'store'])->name('storesick');
    // Route::get('/editsick{id}', [SickController::class, 'editView'])->name('editsick');
    // Route::post('/updatesick{id}', [SickController::class, 'update'])->name('updatesick');
    // Route::get('/destroysick{id}', [SickController::class, 'destroy'])->name('destroysick');

    // Route::get('/object', [AdminObjectController::class, 'index'])->name('object');
    // Route::get('/storeobject', [AdminObjectController::class, 'storeView'])->name('storeobject');
    // Route::post('/storeobject', [AdminObjectController::class, 'store']);
    // Route::get('/editobject{id}', [AdminObjectController::class, 'editView'])->name('editobject');
    // Route::post('/updateobject{id}', [AdminObjectController::class, 'update'])->name('updateobject');
    // Route::get('/destroyobject{id}', [AdminObjectController::class, 'destroy'])->name('destroyobject');


    Route::get('/comment', [AdminCommentController::class, 'index'])->name('comment');
    Route::get('/destroycomment{id}', [AdminCommentController::class, 'destroy'])->name('destroycomment');

    Route::post('/upload-image', [UploadController::class, 'upload'])->name('ckeditor.upload');
});


Route::middleware(['auth', CheckEditorRole::class])->prefix('admin')->group(function () {
    // Các route cho admin và editor (role 1 và 2)


    /**
     * Category
     */
    Route::get('/category', [AdminCategoryController::class, 'index']);
    Route::get('/createcategory', [AdminCategoryController::class, 'createcategory']);
    Route::post('/storecategory', [AdminCategoryController::class, 'storecategory']);
    Route::get('/editcategory{id_category}', [AdminCategoryController::class, 'editcategory']);
    Route::post('/updatecategory{id_category}', [AdminCategoryController::class, 'updatecategory']);
    Route::post('/destroycategory{id_category}', [AdminCategoryController::class, 'destroycategory']);

    Route::get('/secategory', [AdminSeCategoryController::class, 'index']);
    Route::get('/createsecategory', [AdminSeCategoryController::class, 'createsecategory']);
    Route::post('/storesecategory', [AdminSeCategoryController::class, 'storesecategory']);
    Route::get('/editsecategory{id_se_category}', [AdminSeCategoryController::class, 'editsecategory']);
    Route::post('/updatesecategory{id_se_category}', [AdminSeCategoryController::class, 'updatesecategory']);
    Route::post('/destroysecategory{id_se_category}', [AdminSeCategoryController::class, 'destroysecategory']);

    Route::get('/thirdcategory', [AdminThirdCategoryController::class, 'index']);
    Route::get('/createthirdcategory', [AdminThirdCategoryController::class, 'createthirdcategory']);
    Route::post('/storethirdcategory', [AdminThirdCategoryController::class, 'storethirdcategory']);
    Route::get('/editthirdcategory{id_category}', [AdminThirdCategoryController::class, 'editthirdcategory']);
    Route::post('/updatethirdcategory{id_category}', [AdminThirdCategoryController::class, 'updatethirdcategory']);
    Route::post('/destroythirdcategory{id_category}', [AdminThirdCategoryController::class, 'destroythirdcategory']);



    /**
     * Product
     */
    Route::get('/product', [AdminProductController::class, 'index'])->name('product');
    Route::get('/editproduct{id}', [AdminProductController::class, 'edit'])->name('editproduct');
    Route::get('/storeproduct', [AdminProductController::class, 'storeView'])->name('storeproduct');
    Route::post('/storeproduct', [AdminProductController::class, 'store']);
    Route::post('/updateproduct{id}', [AdminProductController::class, 'updateproduct'])->name('updateproduct');
    Route::get('/destroyproduct{id}', [AdminProductController::class, 'destroy'])->name('destroyproduct');
    Route::delete('delete-image/{id}', [ImageController::class, 'deleteImage'])->name('delete.image');


    /**
     * Sick & Object
     */
    Route::get('/sick', [SickController::class, 'index'])->name('sick');
    Route::get('/storeView', [SickController::class, 'storeView'])->name('storeView');
    Route::post('/storesick', [SickController::class, 'store'])->name('storesick');
    Route::get('/editsick{id}', [SickController::class, 'editView'])->name('editsick');
    Route::post('/updatesick{id}', [SickController::class, 'update'])->name('updatesick');
    Route::get('/destroysick{id}', [SickController::class, 'destroy'])->name('destroysick');

    Route::get('/object', [AdminObjectController::class, 'index'])->name('object');
    Route::get('/storeobject', [AdminObjectController::class, 'storeView'])->name('storeobject');
    Route::post('/storeobject', [AdminObjectController::class, 'store']);
    Route::get('/editobject{id}', [AdminObjectController::class, 'editView'])->name('editobject');
    Route::post('/updateobject{id}', [AdminObjectController::class, 'update'])->name('updateobject');
    Route::get('/destroyobject{id}', [AdminObjectController::class, 'destroy'])->name('destroyobject');


    /**
     * User
     */
    Route::get('/users', [AdminUsersController::class, 'index']);
    Route::get('/createusers', [AdminUsersController::class, 'createusers']);
    Route::post('/storeusers', [AdminUsersController::class, 'storeusers']);
    Route::get('/editusers{id_user}', [AdminUsersController::class, 'editusers']);
    Route::post('/updateusers{id_user}', [AdminUsersController::class, 'updateusers']);
    Route::post('/destroyusers{id_user}', [AdminUsersController::class, 'destroyusers']);
    Route::get('/kh', [AdminKhController::class, 'index']);


    /**
     * Insert image trong CKEditor
     */
    Route::post('/upload-image', [UploadController::class, 'upload'])->name('ckeditor.upload');
});

Route::middleware(['auth', CheckEditorPostRole::class])->prefix('admin')->group(function () {
    // Các route cho admin và post editor (role 1 và 3)

    Route::get('/post', [AdminPostsController::class, 'index']);
    Route::get('/createpost', [AdminPostsController::class, 'createpost']);
    Route::post('/storepost', [AdminPostsController::class, 'storepost']);
    Route::get('/editpost{id_post}', [AdminPostsController::class, 'editpost']);
    Route::post('/updatepost{id_post}', [AdminPostsController::class, 'updatepost']);
    Route::post('/destroypost{id_post}', [AdminPostsController::class, 'destroypost']);

    /**
     * Insert image trong CKEditor
     */
    Route::post('/upload-image', [UploadController::class, 'upload'])->name('ckeditor.upload');
});

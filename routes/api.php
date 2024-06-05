<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/{id_category}{id_se_category}', [ProductController::class, 'index']);
// Route::get('/secate{id}', [ProductController::class, 'secate']);
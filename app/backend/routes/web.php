<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BusinessDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;


Route::post('/register', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/admin/register', [AdminController::class, 'registerAdmin']);
Route::post('/admin/login', [AdminController::class, 'loginAdmin']);

Route::get('/admin/get_business_detail', [BusinessDetailController::class, 'getBusinessGeneralDetail']);
Route::get('/admin/get_all_products', [ProductController::class, 'getAllProducts']);
Route::get('/admin/get_product/{id}', [ProductController::class, 'getProductById']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request){
        return $request->user();
    });
    Route::get('/admin', function (Request $request){
        return $request->user();
    });

    Route::post('/user/proceed_to_checkout', [CheckoutController::class, 'addToCheckout']);

    Route::post('/admin/create_buiness_detail', [BusinessDetailController::class, 'createBusinessGeneralDetail']);
    Route::put('/admin/update_detail', [AdminController::class, 'changeAdminDetail']);

    Route::post('/admin/add_product', [ProductController::class, 'createProduct']);
    Route::post('/admin/update_certain_product', [ProductController::class, 'updateProductById']);

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);
});

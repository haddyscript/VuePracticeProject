<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BusinessDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageDetailController;
use App\Http\Controllers\CartController;
use App\Models\Coupon;
use App\Http\Controllers\CouponController;

Route::post('/register', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/user/user_get_all_products', [ProductController::class, 'userGetAllProducts']);


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

    Route::post('/user/add_to_cart', [CartController::class, 'addToCart']);
    Route::post('/user/get_products_in_cart', [CartController::class, 'getProductInCart']);
    Route::post('/user/delete_product_in_cart', [CartController::class, 'removeFromCart']);

    Route::post('/admin/create_buiness_detail', [BusinessDetailController::class, 'createBusinessGeneralDetail']);
    Route::post('/admin/update_detail', [AdminController::class, 'changeAdminDetail']);
    Route::post('/admin/add_coupon', [AdminController::class, 'addCoupon']);

    Route::post('/admin/add_product', [ProductController::class, 'createProduct']);
    Route::post('/admin/update_certain_product', [ProductController::class, 'updateProductById']);
    Route::post('/admin/live_or_unlive_product/{id}', [ProductController::class, 'liveOrUnliveProduct']);
    Route::delete('/admin/delete_certain_product/{id}', [ProductController::class, 'deleteProductById']);

    Route::get('/admin/get_all_coupon', [CouponController::class, 'getAllCoupon']);
    Route::post('/admin/add_coupon', [CouponController::class, 'addCoupon']);
    Route::post('/admin/edit_coupon', [CouponController::class, 'editCoupon']);
    Route::post('/admin/delete_coupon', [CouponController::class, 'deleteCoupon']);

    Route::post('/admin/create_pages', [PageDetailController::class, 'addPagedetails']);
    Route::post('/admin/update_pages', [PageDetailController::class, 'updatePagedetails']);
    Route::get('/admin/get_page', [PageDetailController::class, 'getPage']);
    Route::get('/admin/get_certain_page_to_edit', [PageDetailController::class, 'getCertainPagesDetailToEdit']);
    Route::delete('/admin/delete_page_detail', [PageDetailController::class, 'deletePageDetail']);

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);
});

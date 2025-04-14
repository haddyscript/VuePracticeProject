<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BusinessDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageDetailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OrderBillingDetailsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\XenditPaymentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::post('/xendit/payment_invoice', [XenditPaymentController::class, 'createInvoice']);
Route::post('/xendit/ewallet_payment', [XenditPaymentController::class, 'createEwalletPayment']);
Route::post('/xendit/test_curl_invoice', [XenditPaymentController::class, 'testXenditAPI']);
Route::post('/xendit/webhook', [XenditPaymentController::class, 'handleWebhook']);

Route::post('/xendit/payments', [PaymentController::class, 'handlePayment']);

Route::post('/register', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/user/user_get_all_products', [ProductController::class, 'userGetAllProducts']);
Route::get('/get_country', [CountryController::class, 'getCountry']);

Route::post('/admin/register', [AdminController::class, 'registerAdmin']);
Route::post('/admin/login', [AdminController::class, 'loginAdmin']);

Route::get('/admin/get_business_detail', [BusinessDetailController::class, 'getBusinessGeneralDetail']);
Route::get('/admin/get_all_products', [ProductController::class, 'getAllProducts']);
Route::get('/admin/get_product/{id}', [ProductController::class, 'getProductById']);

Route::get('/migrate-now', function () { //FOR DEPLOYMENT
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration complete!';
});
Route::get('/drop-table/{table}', function ($table) { //FOR DEPLOYMENT
    // Verify if the table exists using schema builder
    $schema = DB::getSchemaBuilder();
    if (!$schema->hasTable($table)) {
        return response("Table '$table' does not exist.", 404);
    }

    // Execute the query to drop the table
    DB::statement("DROP TABLE IF EXISTS $table");

    return response("Table '$table' has been dropped.", 200);
});
Route::get('/drop-all-tables', function () { // FOR DEPLOYMENT - DANGEROUS
    $schema = DB::getSchemaBuilder();
    $tables = $schema->getConnection()->getDoctrineSchemaManager()->listTableNames();

    if (empty($tables)) {
        return response("No tables found to drop.", 404);
    }

    // Disable foreign key checks before dropping
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($tables as $table) {
        Schema::drop($table);
    }

    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    return response("All tables have been dropped.", 200);
});
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
Route::get('/debug-log', function () { //FOR DEPLOYMENT 
    $logFile = storage_path('logs/laravel.log');

    if (!File::exists($logFile)) {
        return response('Log file not found.', 404);
    }

    return response()->file($logFile);
});
Route::get('/clear-debug-log', function () { //FOR DEPLOYMENT
    $logFile = storage_path('logs/laravel.log');

    if (!File::exists($logFile)) {
        return response('Log file not found.', 404);
    }

    File::put($logFile, ''); 
    return response('Log file cleared.', 200);
});
Route::get('/clear-cache', function() { //FOR DEPLOYMENT
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    return response()->json(['message' => 'Cache cleared successfully.']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request){
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => 'false', 'message' => 'User not found'], 404);
        }
        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'gender' => $user->gender,
            'password' => $user->password,
            'date_of_birth' => $user->date_of_birth,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'country' => $user->country,
            'postal_code' => $user->postal_code,
            'profile_picture' => $user->profile_picture ? base64_encode($user->profile_picture) : null,
            'is_active' => $user->is_active,
            'is_verified' => $user->is_verified
        ]);        
    });
    Route::post('/admin', [AdminController::class, 'getAdminInfo']);
    Route::get('/admin_landing_page', [AdminController::class, 'adminLandingPageDetails']);
    Route::post('/admin/update_profile_picture', [AdminController::class, 'uploadProfilePicture']);
    Route::post('/user/update_user', [UserController::class, 'updateUser']);
    Route::post('/user/update_profile_picture', [UserController::class, 'uploadProfilePicture']);
    Route::post('/user/add_to_cart', [CartController::class, 'addToCart']);
    Route::post('/user/get_products_in_cart', [CartController::class, 'getProductInCart']);
    Route::post('/user/delete_product_in_cart', [CartController::class, 'removeFromCart']);
    Route::post('/user/apply_coupon', [CouponController::class, 'applyCoupon']);
    Route::post('/user/get_cart_count', [CartController::class, 'getCartCountItems']);
    Route::post('/user/proceed_to_checkout', [CheckoutController::class, 'addToCheckout']);
    Route::post('/user/checkout_items', [CheckoutController::class, 'getCheckoutDetails']);

    Route::post('/user/place_order', [OrderBillingDetailsController::class, 'addToOrder']);
    Route::post('/user/get_order', [OrderBillingDetailsController::class, 'myOrders']);
    Route::post('/cancel_order', [OrderBillingDetailsController::class, 'cancelOrder']);

    Route::post('admin/get_user_list', [UserController::class, 'adminGetUsersList']);
    Route::post('admin/deactivate_user', [UserController::class, 'deactivateUserAccount']);

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

    Route::post('/admin/get_order_list', [OrderBillingDetailsController::class, 'orderList']);

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/admin/logout', [AdminController::class, 'logout']);
});

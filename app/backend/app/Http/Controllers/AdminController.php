<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\OrderBillingDetails;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function registerAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $findEmailIfExistYet = Admin::where('email', $request->email)->first();
        $findUsernameIfExistYet = Admin::where('username', $request->username)->first();

        $checkPassword = $request->password == $request->confirm_password;
        if(!$checkPassword){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Password and confirm password do not match, please try again.'
            ]), 200);
        }

        if($validator->fails()){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ]), 200);
        }

        if($findEmailIfExistYet){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Email already exists, please use another email. Thank you!'
            ]), 200);
        }
        if($findUsernameIfExistYet){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Username already exists, please use another email. Thank you!'
            ]), 200);
        }

        $admin = Admin::create([
            'email' => $request->email,
            'username' => $request->username,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'password' => bcrypt($request->password),
            'role' => 'admin'
        ]);

        $token = $admin->createToken('admin_auth_token')->plainTextToken;

        return response()->json(([
            'success' => 'true',
            'status' => 'success',
            'message' => 'Admin registered successfully, thank you!',
            'data' => $admin,
            'token' => $token
        ]), 200);
    }

    public function loginAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Validation failed, please check your input!',
                'errors' => $validator->errors()
            ]), 200);
        }

        $admin = Admin::where('email', $request->email)->first();

        if(!$admin){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Email does not exist, please check your input!'
            ]), 200);
        }
        if($admin && !password_verify($request->password, $admin->password)){
            return response()->json(([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Password is incorrect, please check your input!'
            ]), 200);
        }
        if($admin && password_verify($request->password, $admin->password)){
            $token = $admin->createToken('admin_auth_token')->plainTextToken;
            $admin->makeHidden('profile_picture');
            return response()->json(([
                'success' => 'true',
                'status' => 'success',
                'message' => 'Admin logged in successfully, thank you!',
                'admin' => $admin,
                'token' => $token
            ]), 200);
        }
    }

    public function logout(Request $request){
        $admin = $request->user();

        if ($admin && $admin->tokens->isNotEmpty()) {
            $admin->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json(array(
                'status' => 'success',
                'message' => 'Admin logged out successfully!'
            ));
        }else {
            return response()->json(['status' => 'error', 'message' => 'No active tokens to revoke'], 200);
        }
        
        return response()->json(array(
            'status' => 'error',
            'message' => 'Unauthorized!'
        ), 401);
    }

    public function changeAdminDetail(Request $request)
    {
        // Define updatable fields
        $updatableFields = [
            'full_name', 'email', 'password', 'username', 
            'language', 'two_factor_secret', 'email_subscription', 
            'two_factor_enabled', 'location'
        ];

        // Find the first present field in the request
        $change = collect($updatableFields)->first(fn($field) => $request->has($field));

        // Ensure admin_id is provided and valid
        if (!$request->has('admin_id')) {
            return response()->json([
                'message' => 'Admin ID is required.',
                'success' => 'false'
            ], 200);
        }

        $admin = Admin::find($request->admin_id);

        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found.',
                'success' => 'false'
            ], 200);
        }

        // Check if there is a valid field to update
        if (!$change) {
            return response()->json([
                'message' => 'No valid data to update.',
                'success' => 'false'
            ], 200);
        }

        // Validate email uniqueness if it's being changed
        if ($change === 'email' && $request->email !== $admin->email) {
            if (Admin::where('email', $request->email)->exists()) {
                return response()->json([
                    'message' => 'Email is already taken. Please use another email.',
                    'success' => 'false'
                ], 200);
            }
        }

        // Validate username uniqueness if it's being changed
        if ($change === 'username' && $request->username !== $admin->username) {
            if (Admin::where('username', $request->username)->exists()) {
                return response()->json([
                    'message' => 'Username is already taken. Please use another username.',
                    'success' => 'false'
                ], 200);
            }
        }

        // Handle password hashing if updated
        if ($change === 'password') {
            $admin->password = bcrypt($request->password);
        } else {
            $admin->$change = $request->$change;
        }

        // Save updated admin details
        $admin->save();
        $admin->makeHidden('profile_picture');
        return response()->json([
            'success' => 'true',
            'message' => 'Admin details updated successfully!',
            'admin' => $admin
        ], 200);
    }


    public function getAdminInfo(Request $request){

        if(empty($request->admin_id)){
            return response()->json([
                'success' => 'false',
                'message' => 'Can not get admin info, please try again.',
            ], 200);
        }

        $admin = Admin::where('id', $request->admin_id)->first();
        $admin->profile_picture = $admin->profile_picture ? base64_encode($admin->profile_picture) : null;
        return response()->json([
            'success' => 'true',
            'message' => 'Admin info fetched successfully!',
            'admin' => $admin
        ], 200);
    }

    public function uploadProfilePicture(Request $request)
    {
        $admin = Admin::find($request->admin_id);

        if (!$admin) {
            return response()->json(['success' => 'false', 'message' => 'User not found'], 404);
        }

        if (!$request->hasFile('profile_picture')) {
            return response()->json(['success' => 'false', 'message' => 'No file uploaded'], 200);
        }

        $file = $request->file('profile_picture');
        $profilePicture = file_get_contents($file->getRealPath()); 

        $admin->update(['profile_picture' => $profilePicture]);

        return response()->json([
            'success' => 'true',
            'message' => 'Profile picture uploaded successfully',
        ], 200);
    }

    public function adminLandingPageDetails() {
        $totalSales = OrderBillingDetails::where('is_paid', 1)->sum('order_total'); 
        $totalOrders = OrderBillingDetails::whereIn('is_paid', [0, 1])->count();
        $totalProducts = Product::where('live', 1)->count();
        $totalCancelled = OrderBillingDetails::where('is_paid', 2)->sum('order_total'); 
        $inVoiceList = OrderBillingDetails::where('is_paid', 1)->orderByDesc('created_at')->take(5)->get();
        $totalRegisteredUsers = User::count();
        $totalActiveUsers = User::where('is_active', 1)->count();
        $totalProductsQuantity = Product::where('live', 1)->sum('stock_quantity');
        $totalOutOfStockProducts = Product::where('live', 1)->where('stock_quantity', 0)->count();
        $productLists = Product::where('live', 1)->take(5)->orderBy('stock_quantity', 'asc')->get();
        $newOrders = OrderBillingDetails::where('is_paid', 0)->orderByDesc('created_at')->take(5)->get();

        $revenue = $totalSales - $totalCancelled;
        $completedOrders = OrderBillingDetails::where('is_paid', 1)->count(); 
        $averageOrderValue = ($completedOrders > 0) ? $revenue / $completedOrders : 0; 

        $getMethodOfPaymentMostUsed = OrderBillingDetails::where('is_paid', 1)
            ->whereNotNull('mode_of_payment')
            ->groupBy('mode_of_payment')
            ->selectRaw('mode_of_payment, count(*) as count')
            ->orderByDesc('count')
            ->first();

        $paymentMethods = [
            1 => 'COD',
            2 => 'Bank',
            3 => 'PayPal',
            4 => 'Gcash'
        ];

        $mostUsedPaymentMethod = $getMethodOfPaymentMostUsed ? ($paymentMethods[$getMethodOfPaymentMostUsed->mode_of_payment] ?? 'Unknown')  : 'No Data';

        // User Growth (compared to last month)
        $lastMonthUsers = User::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $userGrowth = ($lastMonthUsers > 0) ? (($totalRegisteredUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;

        // Order Fulfillment Rate
        $orderFulfillmentRate = ($totalOrders > 0) ? ($completedOrders / $totalOrders) * 100 : 0;

        // New Order Pending Rate
        $pendingOrders = OrderBillingDetails::where('is_paid', 0)->count();
        $newOrderPendingRate = ($totalOrders > 0) ? ($pendingOrders / $totalOrders) * 100 : 0;

        // Sales Growth This Month (compared to last month)
        $lastMonthSales = OrderBillingDetails::where('is_paid', 1)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('order_total');
        $salesGrowthMonth = ($lastMonthSales > 0) ? (($totalSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Sales Growth This Week (compared to last week)
        $lastWeekSales = OrderBillingDetails::where('is_paid', 1)
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->sum('order_total');
        $salesGrowthWeek = ($lastWeekSales > 0) ? (($totalSales - $lastWeekSales) / $lastWeekSales) * 100 : 0;
        
        return response()->json([
            'success' => 'true',
            'message' => 'Admin landing page details fetched successfully!',
            'totalSales' => $totalSales, 
            'inVoices' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCancelled' => $totalCancelled,
            'inVoiceList' => $inVoiceList,
            'totalRegisteredUsers' => $totalRegisteredUsers,
            'totalActiveUsers' => $totalActiveUsers,
            'totalProductsQuantity' => $totalProductsQuantity,
            'totalOutOfStockProducts' => $totalOutOfStockProducts,
            'revenue' => number_format($revenue, 2),
            'averageOrderValue' => number_format($averageOrderValue, 2),
            'getMethodOfPaymentMostUsed' => $mostUsedPaymentMethod,
            'productLists' => $productLists,
            'newOrders' => $newOrders,
            'userGrowth' => number_format($userGrowth, 2),
            'orderFulfillmentRate' => number_format($orderFulfillmentRate, 2),
            'newOrderPendingRate' => number_format($newOrderPendingRate, 2),
            'salesGrowthMonth' => number_format($salesGrowthMonth, 2),
            'salesGrowthWeek' => number_format($salesGrowthWeek, 2)
        ], 200);
    }

}

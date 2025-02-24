<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Coupon;

class CheckoutController extends Controller
{
    public function addToCheckout (Request $request){

        $validateData = $this->checkData($request);

        if($validateData !== null){
            return $validateData;
        }

        $checkout = new Checkout();
        $checkout->user_id = $request->user_id;
        $checkout->cart_id = json_encode($request->cart_id);
        $checkout->coupon_code = $request->coupon_code;
        if( $checkout->save() ){
            return response()->json([
                'success' => 'true',
                'message' => 'Product added to checkout successfully!',
            ], 200);
        }else{
            return response()->json([
                'success' => 'false',
                'message' => 'Failed to add product to checkout! Please try again!',
            ], 200);
        }
    }

    private function checkData($request){

        if($request->user_id == 0 || $request->user_id < 0 || $request->user_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'User does not exist, please login first!',
            ], 200);
        }
        if($request->product_id == 0 || $request->product_id < 0 || $request->product_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Product does not exist, please select a valid product!',
            ], 200);
        }
        if($request->coupon_code != null){
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if($coupon == null){
                return response()->json([
                    'success' => 'false',
                    'message' => 'Invalid coupon code!',
                ], 200);
            }
            if($coupon->is_active == 0){
                return response()->json([
                    'success' => 'false',
                    'message' => 'Coupon code is not active!',
                ], 200);
            }
            if($coupon->expiry_date < date('Y-m-d')){
                return response()->json([
                    'success' => 'false',
                    'message' => 'Coupon code is expired!',
                ], 200);
            }
            if($coupon->usage_limit < $coupon->used_count){
                return response()->json([
                    'success' => 'false',
                    'message' => 'Coupon code is expired!',
                ], 200);
            }
        }
        return null;
    }
}

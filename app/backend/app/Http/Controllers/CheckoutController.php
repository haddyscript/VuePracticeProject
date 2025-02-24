<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function addToCheckout (Request $request){

        $validateData = $this->checkData($request);

        if($validateData !== null){
            return $validateData;
        }

        DB::beginTransaction();

        try {
            $cartIds = $request->cart_ids;

            if (!is_array($cartIds)) {
                $cartIds = json_decode($cartIds, true);
            }
            $cartIds = array_map('intval', (array) $cartIds);

            if (empty($cartIds)) {
                throw new \Exception('Cart IDs must not be empty.');
            }

            $checkout = new Checkout();
            $checkout->user_id = $request->user_id;
            $checkout->cart_id = json_encode($cartIds);
            $checkout->coupon_code = $request->coupon_code;

            if (!$checkout->save()) {
                throw new \Exception('Failed to save checkout record.');
            }

            $updated = Cart::whereIn('id', $cartIds)->update(['is_checkout' => 1]);
            
            if ($updated === 0) {
                throw new \Exception('Failed to update cart records.');
            }    

            DB::commit();

            return response()->json([
                'success' => 'true',
                'message' => 'Product added to checkout successfully!',
                'checkout' => $checkout
            ], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => 'false',
                'message' => 'Failed to add product to checkout! Please try again!',
                'error'   => $e->getMessage(),
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
        if($request->cart_ids == 0 || $request->cart_ids < 0 || $request->cart_ids == null){
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

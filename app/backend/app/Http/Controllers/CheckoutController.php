<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function getCheckoutDetails(Request $request){
        if($request->user_id == 0 || $request->user_id < 0 || $request->user_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'User does not exist, please login first!',
            ], 200);
        }
        $checkout = Checkout::where('user_id', $request->user_id)->where('is_place_order', 0)->first();
       
        if($checkout){
            $couponCode = $request->coupon_code ?? $checkout->coupon_code;
            $decodeIds = json_decode($checkout->cart_id);
            
            $carts = Cart::whereIn('id', $decodeIds)
                    ->where('is_checkout', 1)
                    ->get();

            if ($carts->isEmpty()) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'No items found in checkout',
                ], 200);
            }else{
                $totalAmount = 0;
                foreach ($carts as $cart) {
                    $product = Product::find($cart->product_id);
                    if ($product) {
                        $cart->product_name = $product->name;
                        $cart->product_price = $product->price;
                        $cart->coupon = $checkout->coupon_code;
                        $cart->checkout_id = $checkout->id;

                        $cart->total_price = $product->price * $cart->quantity;
                        $totalAmount += $cart->total_price; 
                    }
                }
                $discountAmount = 0;
                $finalAmount = $totalAmount;
                if($checkout->coupon_code != null || $request->coupon_code != null){
                    $coupon_code = $request->coupon_code ?? $checkout->coupon_code;
                    $coupon = Coupon::where('code', $coupon_code)
                                    ->where('is_active', 1)
                                    ->whereDate('expiry_date', '>=', now())->first();

                    if (!$coupon) {
                        return $this->generalResponse(false, 'Invalid or expired coupon code ' . $coupon_code . ' !', $carts, $totalAmount, 0, $totalAmount, null);
                    }
                    else{
                        if ($totalAmount < $coupon->min_order_amount) {
                            return $this->generalResponse(false, 'Minimum order amount not met for this coupon!', $carts, $totalAmount, 0, $totalAmount, null);
                        }
                        if ($coupon->discount_type === 'percentage') {
                            $discountAmount = ($totalAmount * $coupon->discount_value) / 100;
                        } else {
                            $discountAmount = $coupon->discount_value;
                        }
                        $finalAmount = max(0, $totalAmount - $discountAmount);
                    }
                }
            }
            
            if( ($request->coupon_code == null || $request->coupon_code == '' || $request->coupon_code == 'null') && $request->is_params == true){
                return $this->generalResponse(false, 'Coupon is empty, please input a valid coupon!', $carts, $totalAmount, $discountAmount, $finalAmount, $couponCode);
            }
            if($request->is_params == true && $request->coupon_code != null){
                return $this->generalResponse(true, "Successfully applied coupon with the discount amount of $" . number_format($discountAmount, 2) . "!", $carts, $totalAmount, $discountAmount, $finalAmount, $couponCode);
            }else{
                return $this->generalResponse(true, 'Checkout details', $carts, $totalAmount, $discountAmount, $finalAmount, $couponCode);
            }
        }
    }

    private function generalResponse($success, $message, $carts, $totalAmount, $discountAmount, $finalAmount, $couponCode)
    {
        return response()->json([
            'success' => $success ? 'true' : 'false',
            'message' => $message,
            'checkout' => $carts,
            'total_amount' => number_format($totalAmount, 2),
            'discount' => number_format($discountAmount, 2),
            'final_amount' => number_format($finalAmount, 2),
            'applied_coupon' => $couponCode,
        ], 200);
    }

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

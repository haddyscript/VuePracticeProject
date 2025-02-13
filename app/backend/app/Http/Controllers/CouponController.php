<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    public function getAllCoupon(){
        $coupons = Coupon::all()->map(function($coupon) {
            return [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'min_order_amount' => $coupon->min_order_amount,
                'usage_limit' => $coupon->usage_limit,
                'used_count' => $coupon->used_count,
                'is_active' => (int) $coupon->is_active,
                'start_date' => date('Y-m-d', strtotime($coupon->start_date)), 
                'expiry_date' => date('Y-m-d', strtotime($coupon->expiry_date)),
            ];
        });
    
        if ($coupons->isEmpty()) {
            return response()->json([
                'success' => 'false',
                'message' => 'No coupon found!',
            ], 200);
        }
    
        return response()->json([
            'success' => 'true',
            'coupons' => $coupons,
        ], 200);
    }

    public function applyCoupon(Request $request){
        
        if($request->code == '' || $request->code == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter coupon code!',
            ], 200);
        }

        $coupon = Coupon::where('code', $request->code)->first();
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

        $couponValidData = [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'min_order_amount' => $coupon->min_order_amount,
        ];
        return response()->json([
            'success' => 'true',
            'coupon' => $couponValidData,
        ], 200);
    }

    public function editCoupon(Request $request){
        $checkValidity = $this->checkValidateBeforeAdding($request, $edit = true);
        if($checkValidity !== null){
            return $checkValidity;
        }   
        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->min_order_amount = $request->min_order_amount;
        $coupon->start_date = $request->start_date;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->used_count = $request->used_count;
        $coupon->is_active = $request->is_active;
        if( $coupon->save() ){
            return response()->json([
                'success' => 'true',
                'message' => 'Coupon updated successfully!',
            ], 200);
        }else{
            return response()->json([
                'success' => 'false',
                'message' => 'Failed to update coupon! Please try again!',
            ], 200);
        }
    }
    
    public function deleteCoupon(Request $request){

        if($request->id == '' || $request->id == null || $request->id == 0){
            return response()->json([
                'success' => 'false',
                'message' => 'Please select a valid coupon!',
            ], 200);
        }
        $coupon = Coupon::find($request->id);
        if( $coupon->delete() ){
            return response()->json([
                'success' => 'true',
                'message' => 'Coupon deleted successfully!',
            ], 200);
        }else{
            return response()->json([
                'success' => 'false',
                'message' => 'Failed to delete coupon! Please try again!',
            ], 200);
        }
    }

    public function addCoupon(Request $request) {
        $checkValidity = $this->checkValidateBeforeAdding($request, $edit = false);
        if($checkValidity !== null){
            return $checkValidity;
        }   
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->min_order_amount = $request->min_order_amount;
        $coupon->start_date = $request->start_date;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->used_count = $request->used_count;
        $coupon->is_active = $request->is_active;
        if( $coupon->save() ){
            return response()->json([
                'success' => 'true',
                'message' => 'Coupon added successfully!',
            ], 200);
        }else{
            return response()->json([
                'success' => 'false',
                'message' => 'Failed to add coupon! Please try again!',
            ], 200);
        }
    }

    private function checkValidateBeforeAdding($request, $edit){
        
        if($edit == true){
            if($request->id == '' || $request->id == null || $request->id == 0){
                return response()->json([
                    'success' => 'false',
                    'message' => 'Please select a valid coupon!',
                ], 200);
            }
        }else{
            $findCoupon = Coupon::where('code', $request->code)->first();
            if($findCoupon != null){
                return response()->json([
                    'success' => 'false',
                    'message' => 'Coupon code already exists!',
                ], 200);
            }
        }

        if($request->discount_type == '' || $request->discount_type == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Please select discount type!',
            ], 200);
        }
        if($request->code == '' || $request->code == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter coupon code!',
            ], 200);
        }
        if($request->discount_value == '' || $request->discount_value == null || $request->discount_value == 0){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter discount value!',
            ], 200);
        }
        if($request->min_order_amount == '' || $request->min_order_amount == null || $request->min_order_amount == 0){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter minimum order amount!',
            ], 200);
        }
        if($request->start_date == '' || $request->start_date == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter start date!',
            ], 200);
        }
        if($request->expiry_date == '' || $request->expiry_date == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter end date!',
            ], 200);
        }
        if($request->usage_limit == '' || $request->usage_limit == null || $request->usage_limit == 0){
            return response()->json([
                'success' => 'false',
                'message' => 'Please enter usage limit!',
            ], 200);
        }

        return null;
    }


}

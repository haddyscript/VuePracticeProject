<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Log;
use App\Models\Checkout;

class OrderBillingDetailsController extends Controller
{
    public function addToOrder(Request $request) {

        $validatedData = $request->validate([
            'country' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'landmark_company_building' => 'nullable|string|max:255',
            'address' => 'required|string',
            'apartment_suite_unit' => 'nullable|string|max:255',
            'state_country' => 'required|string|max:100',
            'postal_zip' => 'required|string|max:20',
            'email_address' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'order_notes' => 'nullable|string',
            'coupon_code' => 'nullable|string|max:50',
            'product_details' => 'required|json',
            'cart_subtotal' => 'required|min:0',
            'discount_total' => 'required|min:0',
            'order_total' => 'required|min:0',
            'is_paid' => 'required|boolean',
            'user_id' => 'required|integer',
            'mode_of_payment' => 'required|integer'
        ]);
        if (!$request->has('mode_of_payment')) {
            return response()->json(['error' => 'Mode of payment is required'], 400);
        }        
        $validatedData['cart_subtotal'] = (float) str_replace(',', '', $validatedData['cart_subtotal']);
        $validatedData['discount_total'] = (float) str_replace(',', '', $validatedData['discount_total']);
        $validatedData['order_total'] = (float) str_replace(',', '', $validatedData['order_total']);

        $productDetails = json_decode($request->product_details, true);
    
        $checkoutId = $productDetails[0]['checkout_id'] ?? null;
    
        $filteredProductDetails = array_map(function ($product) {
            return collect($product)->except(['coupon', 'created_at', 'updated_at'])->toArray();
        }, $productDetails);
    
        $validatedData['product_details'] = json_encode($filteredProductDetails);
    
        $order = OrderBillingDetails::create([
            'country' => $validatedData['country'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'landmark_company_building' => $validatedData['landmark_company_building'],
            'address' => $validatedData['address'],
            'apartment_suite_unit' => $validatedData['apartment_suite_unit'],
            'state_country' => $validatedData['state_country'],
            'postal_zip' => $validatedData['postal_zip'],
            'email_address' => $validatedData['email_address'],
            'phone' => $validatedData['phone'],
            'order_notes' => $validatedData['order_notes'],
            'coupon_code' => $validatedData['coupon_code'],
            'product_details' => $validatedData['product_details'],
            'cart_subtotal' => $validatedData['cart_subtotal'],
            'discount_total' => $validatedData['discount_total'],
            'order_total' => $validatedData['order_total'],
            'is_paid' => $validatedData['is_paid'],
            'user_id' => $validatedData['user_id'],
            'mode_of_payment' => $validatedData['mode_of_payment'],
        ]);
        
    
        if ($order) {
            if ($checkoutId) {
                Checkout::where('id', $checkoutId)->update(['is_place_order' => 1]);
            }
    
            return response()->json([
                'success' => 'true',
                'message' => 'Order added successfully!',
                'order' => $order
            ], 201);
        }
    
        return response()->json([
            'success' => 'false',
            'message' => 'Failed to add order.'
        ], 200);
    }
    
    
}

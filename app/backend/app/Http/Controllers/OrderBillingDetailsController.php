<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\Checkout;
use App\Models\Product;

class OrderBillingDetailsController extends Controller
{
    public function myOrders(Request $request) {
        if (empty($request['user_id'])) {
            return response()->json(['success' => 'false', 'message' => 'User id is required'], 200);
        }
    
        $orders = OrderBillingDetails::where('user_id', $request['user_id'])->get();
    
        if ($orders->isEmpty()) {
            return response()->json(['success' => 'false', 'message' => 'No orders found'], 200);
        }
    
        $formattedOrders = [];
    
        foreach ($orders as $order) {
            $orderData = $order->toArray();
    
            $orderData['product_details'] = json_decode(
                mb_convert_encoding($order->product_details, 'UTF-8', 'UTF-8'),
                true
            );
    
            $productIds = collect($orderData['product_details'])->pluck('product_id')->all();
    
            $products = Product::whereIn('id', $productIds)->with('productImages')->get()->keyBy('id');
    
            foreach ($orderData['product_details'] as &$product) {
                $productId = $product['product_id'];
                if (isset($products[$productId])) {
                    $productData = $products[$productId];
    
                    $productImages = [];
                    if ($productData->productImages) {
                        foreach ($productData->productImages as $image) {
                            if (!empty($image->image_data)) {
                                $productImages[] = base64_encode($image->image_data);
                            }
                        }
                    }
                    $product['image_data'] = $productImages;
                }
            }
    
            $formattedOrders[] = $orderData;
        }
    
        return response()->json(['success' => 'true', 'message' => 'Orders', 'orders' => $formattedOrders], 200, [], JSON_UNESCAPED_UNICODE);
    }
     
    

    public function addToOrder(Request $request) {
        Log::info('Received request to add order:', $request->all());
        if (empty($request->mode_of_payment)) {
            return response()->json(['success' => 'false', 'message' => 'Mode of payment is required'], 200);
        }
        
        if($request->mode_of_payment == 4) {
            if(!$request->has('gcash_number')) {
                return response()->json(['success' => 'false', 'message' => 'Gcash number is required'], 200);
            }
            $xendit_payment = new XenditPaymentController();
            $params = [
                'ewallet_type' => 'GCASH',
                'amount' => $request->order_total,
                'number' => $request->gcash_number,
                'email' => $request->email_address
            ];
            $payNowResponse = $xendit_payment->createEwalletPayment(new Request($params));

            if (!$payNowResponse instanceof JsonResponse) {
                return response()->json(['success' => 'false', 'message' => 'Unexpected error from payment provider'], 500);
            }
            Log::info('Raw payNowResponse:', [$payNowResponse]);
            $payNow = $payNowResponse->getData(true);
            Log::info('payNow', $payNow);
            if (!isset($payNow['status']) || $payNow['status'] !== 'PENDING') {
                return response()->json(['success' => 'false', 'message' => 'Payment failed with error: ' . ($payNow['message'] ?? 'Unknown error')], 200);
            }
        }

        $validatedData = $this->validateOrderData($request);
        $validatedData['payment_reference'] = $payNow['reference_id'] ?? null;

        $order = OrderBillingDetails::create($validatedData);
    
        if ($order && isset($validatedData['checkout_id'])) {
            Checkout::where('id', $validatedData['checkout_id'])->update(['is_place_order' => 1]);
        }
        
        return response()->json([
                'success' => 'true',
                'message' => 'Order added successfully!',
                'order' => $order,
                'redirect_url' => $payNow['redirect_url'] ?? null
            ], 201);
    }
    
    private function validateOrderData(Request $request) {
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
        
        $validatedData['cart_subtotal'] = (float) str_replace(',', '', $validatedData['cart_subtotal']);
        $validatedData['discount_total'] = (float) str_replace(',', '', $validatedData['discount_total']);
        $validatedData['order_total'] = (float) str_replace(',', '', $validatedData['order_total']);
    
        $productDetails = json_decode($request->product_details, true);
        $validatedData['checkout_id'] = $productDetails[0]['checkout_id'] ?? null;
        $validatedData['product_details'] = json_encode(array_map(fn($p) => collect($p)->except(['coupon', 'created_at', 'updated_at'])->toArray(), $productDetails));
        
        return $validatedData;
    }
        
}

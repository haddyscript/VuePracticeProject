<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\Checkout;
use App\Models\Product;

class OrderBillingDetailsController extends Controller
{
    public function myOrders(Request $request) {
        if (empty($request['user_id'])) {
            return response()->json(['success' => 'false', 'message' => 'User id is required'], 200);
        }
    
        if(isset($request['history']) && !empty($request['history'])) {
            $orders = OrderBillingDetails::where('user_id', $request['user_id'])->where('is_paid', '!=', 0)->orderBy('created_at', 'desc')->get();
        }else{
            $orders = OrderBillingDetails::where('user_id', $request['user_id'])->where('is_paid', '!=', 2)->orderBy('created_at', 'desc')->get();
        }
    
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
     
    public function orderList(Request $request) {
        $search = $request->get('search', []);
        $page = isset($request->get('status')['page']) ? $request->get('status')['page'] : 1;
        $perPage = 15;

        $query = OrderBillingDetails::query();
        
        if (isset($search['order_id']) && !empty($search['order_id'])) {
            $orderId = ltrim($search['order_id'], '#');
            $query->where('id', $orderId);
        }
          
    
        if (isset($search['this_week']) && !empty($search['this_week'])) {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }
        if (isset($search['this_month']) && !empty($search['this_month'])) {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        }
        if (isset($search['last_3_months']) && !empty($search['last_3_months'])) {
            $query->whereBetween('created_at', [now()->subMonths(3)->startOfMonth(), now()->endOfMonth()]);
        }
    
        if (isset($search['for_paid']) && !empty($search['for_paid'])) {
            $query->where('is_paid', 1);
        } elseif (isset($search['for_pending']) && !empty($search['for_pending'])) {
            $query->where('is_paid', 0);
        } elseif (isset($search['for_cancelled']) && !empty($search['for_cancelled'])) {
            $query->where('is_paid', 2);
        }
        $query->orderBy('created_at', 'desc');
        $order_products = $query->paginate($perPage, ['*'], 'page', $page);

        $order_data = collect($order_products->items())->map(function ($order) {
            $productDetails = json_decode($order->product_details, true);
            
            $order->product_names = array_column($productDetails, 'product_name');
    
            return $order;
        });
        $totalCount = $query->count();

        return response()->json([
            'success' => true,
            'order_products' => $order_data, 
            'total_count' => $totalCount,
            'pagination' => [
                'current_page' => $order_products->currentPage(),
                'last_page' => $order_products->lastPage(),
                'per_page' => $order_products->perPage(),
                'total' => $order_products->total(),
            ],
        ], 200);
    }        
    

    public function addToOrder(Request $request) {
        Log::info('Received request to add order:', $request->all());
        if (empty($request->mode_of_payment)) {
            return response()->json(['success' => 'false', 'message' => 'Mode of payment is required'], 200);
        }
        if($request->address == null || $request->address == '' || $request->address == 'null') {
            return response()->json(['success' => 'false', 'message' => 'Please enter your address for shipping, Thank you!'], 200);
        }
        if($request->state_country == null || $request->state_country == '' || $request->state_country == 'null') {
            return response()->json(['success' => 'false', 'message' => 'Please enter your state or country for shipping, Thank you!'], 200);
        }
        if($request->country == null || $request->country == '' || $request->country == 'null') {
            return response()->json(['success' => 'false', 'message' => 'Please enter your country for shipping, Thank you!'], 200);
        }
        if($request->postal_zip == null || $request->postal_zip == '' || $request->postal_zip == 'null') {
            return response()->json(['success' => 'false', 'message' => 'Please enter your Postal or Zip Code for shipping, Thank you!'], 200);
        }
        if($request->phone == null || $request->phone == '' || $request->phone == 'null') {
            return response()->json(['success' => 'false', 'message' => 'Please enter your Phone Number for shipping, Thank you!'], 200);
        }
        if($request->email_address == null || $request->email_address == '' || $request->email_address == 'null') {
            return response()->json(['success' => 'false', 'message' => 'Please enter your email address for shipping, Thank you!'], 200);
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
        $validatedData['id'] = $this->generateUniqueOrderId();

        $order = OrderBillingDetails::create($validatedData);
    
        if ($order && isset($validatedData['checkout_id'])) {
            Checkout::where('id', $validatedData['checkout_id'])->update(['is_place_order' => 1]);
        }

        if (!empty($request->product_details)) {
            $productDetails = json_decode($request->product_details, true);
            
            foreach ($productDetails as $product) {
                $productModel = Product::where('id', $product['product_id'])->first();
                
                if ($productModel && $productModel->stock_quantity >= $product['quantity']) {
                    $productModel->decrement('stock_quantity', $product['quantity']);
                } else {
                    return response()->json([
                        'success' => 'false',
                        'message' => "Not enough stock for product: {$product['product_name']}"
                    ], 200);
                }
            }
        }
        
        return response()->json([
                'success' => 'true',
                'message' => 'Order added successfully!',
                'order' => $order,
                'redirect_url' => $payNow['redirect_url'] ?? null
            ], 201);
    }
    private function generateUniqueOrderId()
    {
        do {
            // Generate a random 5-digit number
            $randomNumber = rand(10000, 99999);
            $orderId = $randomNumber;

            $exists = OrderBillingDetails::where('id', $orderId)->exists();
        } while ($exists);

        return $orderId;
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

    public function cancelOrder(Request $request) {
        
        $orderId = $request->order_id;

        if(empty($orderId)) {
            return response()->json(['success' => 'false', 'message' => 'Order id is required'], 200);
        }

        $order = OrderBillingDetails::where('id', $orderId)->first();

        if(empty($order)) {
            return response()->json(['success' => 'false', 'message' => 'Order not found'], 200);   
        }

        $order->update(['is_paid' => 2]);

        return response()->json(['success' => 'true', 'message' => 'Order cancelled successfully'], 200);
    }
        
}

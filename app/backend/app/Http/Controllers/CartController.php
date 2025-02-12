<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CartController extends Controller
{
    public function addToCart(Request $request){
        // Log::info('Add to cart request: ', $request->all());
        $checkValidity = $this->checkValidate($request);

        if($checkValidity !== null){
            return $checkValidity;
        }
        $user_id = $request->user()->id;
        $cart = Cart::where('user_id', $user_id)->where('product_id', $request->product_id)->first();
        $timeInAsia = Carbon::now('Asia/Manila');

        if($cart == null){
            $cart = new Cart();
            $cart->user_id = $user_id;
            $cart->product_id = $request->product_id;
            $cart->quantity = $request->quantity;
            $cart->created_at =  $timeInAsia->toDateTimeString();
            $cart->save();
            return response()->json([
                'success' => 'true',
                'message' => 'Product added to cart successfully!',
            ], 200);
        }
        else{
            $cart->quantity = $request->quantity;   
            $cart->save();
            return response()->json([
                'success' => 'true',
                'message' => 'Product quantity updated successfully!',
            ], 200);
        }

    }

    private function checkValidate($request){

        if($request->isLogin == false || $request->isLogin == 'false'){
            return response()->json([
                'success' => 'false',
                'message' => 'Please login first!',
            ], 200);
        }
        $user_id = $request->user()->id;
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if($request->quantity <= 0){
            return response()->json([
                'success' => 'false',
                'message' => 'Please select a valid quantity!',
            ], 200);
        }
        if($request->product_id == 0 || $request->product_id < 0 || $request->product_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Product does not exist, please select a valid product!',
            ], 200);
        }
        if( $user_id == 0 ||  $user_id < 0 ||  $user_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'User does not exist, please login first!',
            ], 200);
        }
        $product = Product::find($request->product_id);

        if($product == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Product does not exist, please select a valid product!',
            ], 200);
        }
        if($product->stock_quantity < $request->quantity || $product->stock_status == 'out_of_stock'){
            return response()->json([
                'success' => 'false',
                'message' => 'Product is out of stock!',
            ], 200);
        }
        if($product->live == 0 || $product->stock_status == 'preorder' || $product->status == 0){
            return response()->json([
                'success' => 'false',
                'message' => 'Product is not available right now, please try again later!',
            ], 200);
        }

        return null;
    }

    public function getProductInCart(Request $request){
        $user_id = $request->user_id;
        if($user_id == 0 || $user_id < 0 || $user_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'User does not exist, please login first!',
            ], 200);
        }
        $cart = Cart::where('user_id', $user_id)->get();
        $products = [];
        
        foreach($cart as $cart_items){
            $product = Product::with('productImages')->where('id', $cart_items->product_id)->first();
            
            foreach ($product->productImages as $image) {
                $image->image_data = base64_encode($image->image_data);
            }

            $products[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $image->image_data,
                'quantity' => $cart_items->quantity,
            ];
        }
        return response()->json([
            'success' => 'true',
            'message' => 'Products in cart',
            'products' => $products,
        ], 200);
    }

    public function removeFromCart(Request $request){

        $checkValidity = $this->validateRemoveFromCart($request);

        if($checkValidity !== null){
            return $checkValidity;
        }
        $product_id = $request->product_id;
        $user_id = $request->user_id;

        $cart = Cart::where('product_id', $product_id)->where('user_id', $user_id)->first();

        if($cart != null){

            if($cart->delete())
            {
                return response()->json([
                    'success' => 'true',
                    'message' => 'Product removed from cart successfully!',
                ], 200);
            }else{
                return response()->json([
                    'success' => 'false',
                    'message' => 'Product could not be removed from cart, please try again!',
                ], 200);
            }
        }

    }
    
    private function validateRemoveFromCart($request){
        $user_id = $request->user_id;
        $product_id = $request->product_id;

        if($user_id == 0 || $user_id < 0 || $user_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'User does not exist, please login first!',
            ], 200);
        }
        if($product_id == 0 || $product_id < 0 || $product_id == null){
            return response()->json([
                'success' => 'false',
                'message' => 'Product does not exist, please select a valid product!',
            ], 200);
        }

        return null;
    }
}

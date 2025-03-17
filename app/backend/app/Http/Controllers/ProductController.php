<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\OrderBillingDetails;
use Illuminate\Support\Facades\Log;
use App\Models\Checkout;

class ProductController extends Controller
{
    public function createProduct(Request $request){
        // Log::info('Incoming Request : ' . var_export($request->all(), true));
        $checkValidity = $this->ProductCheck($request);

        if ($checkValidity !== null) {
            return $checkValidity;
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'size' => $request->size,            
            'brand' => $request->brand,
            'stock_quantity' => $request->stock_quantity,
        ]);

        if (!$product) {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'Product creation failed, please try again!',
            ]);
        }

        if ( isset($request) && isset($request->image) ) {
            $image = $request->file('image');
    
            $imageData = file_get_contents($image->getRealPath());
    
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'name' => $request->name,
                'image_data' => $imageData, 
            ]);
    
            if (!$productImage) {
                return response()->json([
                    'success' => "false",
                    'status' => 'error',
                    'message' => 'Product image creation failed, please try again!',
                ]);
            }
        }
        
        return response()->json([
            'success' => "true",
            'status' => 'success',
            'message' => 'Product created successfully!',
            'product' => $product,
        ]);
    }

    private function ProductCheck($request){

        $checkName = Product::where('name', $request->name)->first();
        if( $checkName ) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => 'Product name already exists, please try again!',
            ]);
        }

        $checkNull = match(true) {
            $request->name == '' => 'Product name must not be empty!',
            $request->description == null => 'Product description must not be empty!',
            $request->price == null => 'Product price must not be empty!',
            $request->color == null => 'Product color must not be empty!',
            $request->brand == null => 'Product brand must not be empty!',
            $request->stock_quantity == null => 'Product stock quantity must not be empty!',
            $request->image == null => 'Product image must not be empty!',
            $request->image == [] => 'Product image must not be empty!',
            default => null,
        };

        if ($checkNull !== null) {
            return response()->json([
                'success' => 'false',
                'status' => 'error',
                'message' => $checkNull,
                'product' => $request
            ],200);
        }

        return null;
        
    }

    public function userGetAllProducts(Request $request) {

        $page = $request->get('status')['page'] ?? 1;
        $perPage = 12;

        // Log::info('Incoming Request : ' . var_export($request->all(), true));

        $query = Product::with('productImages')->where('live', 1);
    
        $products = $query->paginate($perPage, ['*'], 'page', $page);
    
        foreach ($products as $product) {
            if ($product->productImages) {
                foreach ($product->productImages as $image) {
                    $image->image_data = base64_encode($image->image_data);
                }
            }
        }
    
        return response()->json([
            'success' => "true",
            'status' => 'success',
            'message' => 'Products fetched successfully!',
            'products' => $products->items(), 
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function getAllProducts(Request $request) {
        $status = $request->get('status', 'all'); // Default to 'all'
        $name = $request->get('status')['name'] ?? null;
        $page = $request->get('status')['page'] ?? 1;
        $perPage = 12;

        // Log::info('Incoming Request : ' . var_export($request->all(), true));

        $query = Product::with('productImages');
        if (!empty($name)) {
            $query->where('name', 'LIKE', "%$name%");
        }

        switch ($status) {
            case 'live':
                $query->where('live', 1);
                break;
            case 'in_stock':
                $query->where('stock_quantity', '>', 0);
                break;
            case 'out_of_stock':
                $query->where('stock_quantity', '=', 0);
                break;
            case 'low_stock':
                $query->where('stock_quantity', '<=', 5); 
                break;
            case 'pre-order':
                $query->where('status', 'pre-order');
                break;
            case 'active':
                $query->where('status', 'active');
                break;
            case 'inactive':
                $query->where('status', 'inactive');
                break;
        }
    
        $products = $query->paginate($perPage, ['*'], 'page', $page);
    
        foreach ($products as $product) {
            if ($product->productImages) {
                foreach ($product->productImages as $image) {
                    $image->image_data = base64_encode($image->image_data);
                }
            }
        }
    
        return response()->json([
            'success' => "true",
            'status' => 'success',
            'message' => 'Products fetched successfully!',
            'products' => $products->items(), 
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }
    

    public function getProductById($id)
    {
        if (empty($id)) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'An error occurred, No product Id included, Thank you!',
            ]);
        }

        $findProduct = Product::with('productImages')->where('id', $id)->first();
        foreach ($findProduct->productImages as $image) {
            $image->image_data = base64_encode($image->image_data);
        }
        
        if (!$findProduct) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'No product found, thank you!',
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Product found!',
            'product' => $findProduct
        ]);
    }

    public function updateProductById(Request $request){
        // Log::info('Incoming Request : ' . var_export($request->all(), true));
        $checkProduct = Product::where('id', $request->id)->first();

        if($this->ProductCheckBeforeUpdate($checkProduct, $request) !== null){
            return $this->ProductCheckBeforeUpdate($checkProduct, $request);
        }

        $product = Product::where('id', $request->id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'color' => $request->color,
            'brand' => $request->brand,
            'stock_quantity' => $request->stock_quantity,
            'material' => $request->material,
            'dimensions' => $request->dimensions,
            'manufacturer' => $request->manufacturer,
            'weight' => $request->weight
        ]);

        if (!$product) {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'Product update failed, please try again!',
            ]);
        }

        if( isset($request->image_data) && $request->image_data !== null ) {
            
            $imageData = $request->image_data;

            $request->validate([
                'image_data' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            ]);

            $imageBlob = file_get_contents($request->file('image_data')->getRealPath());

            $image = ProductImage::updateOrCreate(
                ['product_id' => $request->id],
                ['image_data' => $imageBlob]
            );

            if (!$image) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Product image update failed, please try again!',
                ]);
            }
        }

        return response()->json([
            'success' => "true",
            'status' => 'success',
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);

    }


    private function ProductCheckBeforeUpdate($checkProduct, $request){

        if($request->id == null || $request->id == '') {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'Product Id must not be empty, thank you!',
                'product' => $request->all()
            ]);
        }

        if (!$checkProduct) {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'No product found in the database, thank you!',
                'product' => $request->all()
            ]);
        }
        
        if(Product::where('name', $request->name )->where('brand', $request->brand)->where('id', '!=', $request->id)->first()){
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'Product name  already exists, please try again!',
            ]);
        }

        return null;
    }

    public function deleteProductById($id){

        $findThisProductIfCheckoutExist = Cart::where('product_id', $id)->first();
        $findThisProductIfOrderExist = OrderBillingDetails::whereRaw(
            "JSON_CONTAINS(product_details, ?, '$')", 
            [json_encode(['product_id' => (int) $id])]
        )->first();
        

        if($findThisProductIfCheckoutExist || $findThisProductIfOrderExist){
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'This product is currently in use. Please check again later or contact support for assistance.',                              
            ], 200);
        }

        $image = ProductImage::where('product_id', $id)->delete();
        if (!$image) {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'Product delete failed, please try again!',
            ]);
        }
        if($image){
            $product = Product::where('id', $id)->delete();
            if(!$product){
                return response()->json([
                    'success' => "false",
                    'status' => 'error',
                    'message' => 'Product image delete failed, please try again!',
                ]);
            }
        }
        return response()->json([
            'success' => "true",
            'status' => 'success',
            'message' => 'Product deleted successfully!',
        ]);
    }

    public function liveOrUnliveProduct($id){

        $product = Product::where('id', $id)->first();
        if (!$product) {
            return response()->json([
                'success' => "false",
                'status' => 'error',
                'message' => 'Product not found, please try again!',
            ]);
        }

        $product->live = $product->live == 1 ? 0 : 1;
        $product->save();

        return response()->json([
            'success' => "true",
            'status' => 'success',
            'message' => 'Product live status updated successfully!',
            'product' => $product
        ]);
    }

}

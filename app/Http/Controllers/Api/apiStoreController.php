<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class apiStoreController extends Controller
{
    public function getStoreDetails(Request $request)
    {
        try {
            // Get store_id from the request
            $store_id = $request->query('store_id');

            if (!$store_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Store ID is required'
                ], 400);
            }

            // Fetch store details
            $store = Store::select('store_id', 'store_profile_detail', 'store_info')
                ->where('store_id', $store_id)
                ->first();

            if (!$store) {
                return response()->json([
                    'success' => false,
                    'message' => 'Store not found'
                ], 404);
            }

            // Decode store details
            $storeProfileDetail = json_decode($store->store_profile_detail, true);
            $storeInfo = json_decode($store->store_info, true);

            // Fetch all products of the store
            $products = Products::select(
                "product_id",
                "store_id",
                "product_name",
                "product_brand",
                "product_category",
                "product_subcategory",
                "product_price",
                "product_discount",
                "product_discounted_price",
                "product_images"
            )
                ->where('store_id', $store_id)
                ->with(['category:id,name']) // Fetch category details
                ->get();

            // Process product images and add category name
            foreach ($products as $product) {
                $product->product_images = json_decode($product->product_images, true);
                $product->product_image = $product->product_images[0] ?? null;
                unset($product->product_images);

                // Default product rating
                $product->product_rating = 4.5;

                // Add category name
                $product->category_name = $product->category->name ?? null;
                unset($product->category); // Remove full category object

                // Add is_discounted flag
                $product->is_discounted = $product->product_discount > 0;
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Store details fetched successfully',
                'store'    => $storeProfileDetail ?: $storeInfo, // Prioritize store profile details
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success'  => false,
                'message'  => $e->getMessage()
            ], 500);
        }
    }
}

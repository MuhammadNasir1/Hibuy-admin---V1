<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Query;
use Carbon\Carbon;

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

            // Prioritize store profile details if available
            $storeData = $storeProfileDetail ?: $storeInfo;

            // Add static store_rating into the store data
            $storeData['store_rating'] = 4.5; // You can change 4.5 to any default rating you want

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

            // Return success response
            return response()->json([
                'success'  => true,
                'message'  => 'Store details fetched successfully',
                'store'    => $storeData, // Store object with added store_rating
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success'  => false,
                'message'  => $e->getMessage()
            ], 500);
        }
    }



    public function getStoreList(Request $request)
    {
        try {
            // Fetch 6 stores
            $stores = Store::select('store_id', 'store_profile_detail')
                ->whereHas('user',function ($query) {
                    $query->where('user_status', 1); // Ensure the user is active
                })
                // ->limit(6)
                ->get();

            if ($stores->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No stores found'
                ], 404);
            }

            $storeList = $stores->map(function ($store) {
                $profileDetail = json_decode($store->store_profile_detail, true);

                $storeImage = $profileDetail['store_image'] ?? null;

                return [
                    'store_id'    => $store->store_id,
                    'store_name'  => $profileDetail['store_name'] ?? null,
                    'store_image' => !empty($storeImage) ? $storeImage : asset('asset/default-image.png'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Store List fetched successfully',
                'stores'  => $storeList,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

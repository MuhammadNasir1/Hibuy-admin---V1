<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\product_category;
use Illuminate\Support\Facades\DB as FacadesDB;

class apiStoreController extends Controller
{
    public function getStoreDetails(Request $request)
    {
        try {
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
            $storeData = $storeProfileDetail ?: $storeInfo;
            $storeData['store_rating'] = 4.5;

            // Fetch products
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
                ->where('product_status', '=', 1)
                ->whereHas('user', function ($q) {
                    $q->where('user_status', 1);
                })
                ->with(['category:id,name'])
                ->get();

            foreach ($products as $product) {
                $product->product_images = json_decode($product->product_images, true);
                $product->product_image = $product->product_images[0] ?? null;
                unset($product->product_images);

                $product->product_rating = 4.5;
                $product->category_name = $product->category->name ?? null;
                unset($product->category);

                $product->is_discounted = $product->product_discount > 0;
            }

            // Get product IDs
            $productIds = $products->pluck('product_id');

            // Categories from pivot table (deep child categories)
            $deepCategoryIds = FacadesDB::table('product_category_product')
                ->whereIn('product_id', $productIds)
                ->pluck('category_id')
                ->toArray();

            // Main categories from products table
            $mainCategoryIds = $products->pluck('product_category')->toArray();

            // Merge & remove duplicates
            $allCategoryIds = array_unique(array_merge($mainCategoryIds, $deepCategoryIds));

            // Fetch category details
            $categories = product_category::whereIn('id', $allCategoryIds)
                ->select('id', 'name', 'category_type', 'image', 'parent_id')
                ->get()
                ->toArray();

            /**
             * Build category tree recursively
             */
            $buildTree = function ($categories, $parentId = null) use (&$buildTree) {
                $branch = [];
                foreach ($categories as $category) {
                    if ($category['parent_id'] == $parentId) {
                        $children = $buildTree($categories, $category['id']);
                        if ($children) {
                            $category['children'] = $children;
                        }
                        $branch[] = $category;
                    }
                }
                return $branch;
            };

            $categoryTree = $buildTree($categories);

            // Return Response
            return response()->json([
                'success' => true,
                'message' => 'Store details fetched successfully',
                'store' => $storeData,
                'products' => $products,
                'categories' => $categoryTree
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }





    public function getStoreList(Request $request)
    {
        try {
            // Fetch 6 stores
            $stores = Store::select('store_id', 'store_profile_detail')
                ->whereHas('user', function ($query) {
                    $query->where('user_status', 1); // Ensure the user is active
                })
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
                    'store_id' => $store->store_id,
                    'store_name' => $profileDetail['store_name'] ?? null,
                    'store_image' => !empty($storeImage) ? $storeImage : asset('asset/default-image.png'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Store List fetched successfully',
                'stores' => $storeList,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\product_category;
use App\Models\Wishlist;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class apiproductController extends Controller
{
    public function getProducts(Request $request)
{
    try {
        // Get filter values from both GET (query params) and POST (request body)
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $subcategory = $request->input('product_subcategory');

        // Fetch products with category name and subcategory (direct from table)
        $query = Products::select(
            "product_id",
            "store_id",
            "product_name",
            "product_brand",
            "product_category",
            "product_subcategory", // Directly fetching from the table
            "product_price",
            "product_discount",
            "product_discounted_price",
            "product_images"
        )
            ->with(['category:id,name']); // Fetch category details

        // Apply price range filter if provided
        if (!empty($min_price)) {
            $query->where('product_discounted_price', '>=', $min_price);
        }

        if (!empty($max_price)) {
            $query->where('product_discounted_price', '<=', $max_price);
        }

        // Apply product subcategory filter if provided
        if (!empty($subcategory)) {
            $query->where('product_subcategory', $subcategory);
        }

        $products = $query->get();

        // Process product images and add category name
        foreach ($products as $product) {
            $product->product_images = json_decode($product->product_images, true);
            $product->product_image = $product->product_images[0] ?? null;
            unset($product->product_images);

            // Default product rating
            $product->product_rating = 4.5;

            // Add category name
            $product->category_name = $product->category->name ?? null;
            unset($product->category); // Remove the full category object

            // Add is_discounted flag
            $product->is_discounted = $product->product_discount > 0 ? true : false;
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Products Fetched Successfully',
            'products' => $products
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success'  => false,
            'message'  => $e->getMessage()
        ], 500);
    }
}






    public function getProductsDetail(Request $request)
    {
        try {
            // Get product_id from the request
            $product_id = $request->query('product_id');

            if (!$product_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }

            // Fetch product with store and category details
            $product = Products::select(
                'product_id',
                'product_name',
                'product_description',
                'product_price',
                'product_discount',
                'product_discounted_price',
                'product_images',
                'product_variation',
                'store_id',
                'product_category', // Category ID
                'product_subcategory' // Subcategory ID
            )
                ->with([
                    'store:store_id,store_profile_detail,store_info', // Fetch store details
                    'category:id,name' // Corrected category relation
                ])
                ->where('product_id', $product_id)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Decode JSON fields from products
            $product->product_images = json_decode($product->product_images, true);
            $product->product_variation = json_decode($product->product_variation, true);

            // Prepare response data
            $response = [
                'product_id'               => $product->product_id,
                'product_name'             => $product->product_name,
                'product_description'      => $product->product_description,
                'product_price'            => $product->product_price,
                'product_discount'         => $product->product_discount,
                'product_discounted_price' => $product->product_discounted_price,
                'product_images'           => $product->product_images,
                'product_variation'        => $product->product_variation,
                'category_id'              => $product->category->id ?? null,
                'category_name'            => $product->category->name ?? null
            ];

            // Handle store details
            if ($product->store) {
                $storeProfileDetail = json_decode($product->store->store_profile_detail, true);

                if (!empty($storeProfileDetail)) {
                    $response['store_profile_detail'] = $storeProfileDetail;
                } else {
                    $response['store_info'] = json_decode($product->store->store_info, true);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Product Fetched Successfully',
                'product' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }






    public function toggleWishlist(Request $request)
    {
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        // Validate request
        $request->validate([
            'product_id' => 'required|integer',
            // |exists:products,product_id
        ]);

        $userId = $user->user_id;
        $productId = $request->product_id;

        // Check if product is already in wishlist
        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            // Product exists in the wishlist, remove it
            $wishlistItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from wishlist',
            ], 200);
        } else {
            // Product does not exist, add it
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist',
            ], 201);
        }
    }

    public function getCategories()
    {
        try {
            // Check if user is authenticated
            // $user = Auth::user();
            // if (!$user) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'User not authenticated',
            //     ], 401);
            // }

            // Fetch categories and decode JSON sub_categories
            $categories = product_category::select('id', 'name', 'image', 'sub_categories')
                ->get()
                ->map(function ($category) {
                    $category->sub_categories = json_decode($category->sub_categories, true);
                    return $category;
                });

            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}

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
    public function getProducts(Request $request, $categoryid = null)
    {
        try {
            // Fetch products with category name and subcategory
            $query = Products::select(
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
                ->with(['category:id,name']);

            // Apply category filter
            if (!empty($categoryid)) {
                $query->where('product_category', $categoryid);
            }

            // Apply subcategory filter if provided
            if ($request->has('product_subcategory') && !empty($request->product_subcategory)) {
                $query->where('product_subcategory', $request->product_subcategory);
            }

            // Apply min_price filter if provided
            if ($request->has('min_price') && !empty($request->min_price)) {
                $query->where('product_discounted_price', '>=', $request->min_price);
            }

            // Apply max_price filter if provided
            if ($request->has('max_price') && !empty($request->max_price)) {
                $query->where('product_discounted_price', '<=', $request->max_price);
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
                $product->is_discounted = $product->product_discount > 0;
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

            // Fetch product with store, category, and reviews
            $product = Products::select(
                'product_id',
                'product_name',
                'product_description',
                'product_price',
                'product_brand',
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
                    'category:id,name', // Fetch category details
                    'reviews.user:user_id,user_name' // Fetch reviews along with the user's name
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
                'product_brand'            => $product->product_brand,
                'product_discount'         => $product->product_discount,
                'product_discounted_price' => $product->product_discounted_price,
                'product_images'           => $product->product_images,
                'product_variation'        => $product->product_variation,
                'category_id'              => $product->category->id ?? null,
                'category_name'            => $product->category->name ?? null,
                'review_count'             => $product->reviews->count(), // Count total reviews
                'reviews'                  => []
            ];

            // Attach store details
            if ($product->store) {
                $storeProfileDetail = json_decode($product->store->store_profile_detail, true);
                $response['store_profile_detail'] = $storeProfileDetail ?: json_decode($product->store->store_info, true);
            }

            // Attach reviews
            foreach ($product->reviews as $review) {
                $response['reviews'][] = [
                    'review_id' => $review->review_id,
                    'user_id'   => $review->user_id,
                    'username'  => $review->user->user_name ?? 'Unknown', // Fetch username
                    'rating'    => $review->rating,
                    'review'    => $review->review,
                    'images'    => $review->images,
                    'review_date' => $review->created_at->format('d F, Y')
                ];
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

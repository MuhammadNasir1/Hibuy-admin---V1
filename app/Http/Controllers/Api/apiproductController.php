<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\product_category;
use App\Models\Wishlist;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DashboardBanner;
use Illuminate\Support\Facades\Auth;

class apiproductController extends Controller
{

    public function dashboardProductsList()
    {
        try {
            // Fetch 16 random products (10+6)
            $allProducts = Products::select(
                "product_id",
                "store_id",
                "product_name",
                "product_brand",
                "product_category",
                "product_subcategory",
                "product_price",
                "product_discount",
                "product_discounted_price",
                "product_images",
                "created_at",
                "updated_at"
            )
                ->where('store_id', '!=', 0)
                ->with(['category:id,name'])
                ->inRandomOrder()
                ->limit(18)
                ->get();

            // Split into products and foryouproducts
            $products = $allProducts->take(10);
            $foryouproducts = $allProducts->slice(10); // remaining 6

            // Process both lists
            $processProducts = function ($items) {
                foreach ($items as $product) {
                    $product->product_images = json_decode($product->product_images, true);
                    $product->product_image = $product->product_images[0] ?? null;
                    unset($product->product_images);

                    // Generate random rating between 3.5 and 5.0
                    $product->product_rating = round(mt_rand(35, 50) / 10, 1);
                    $product->category_name = $product->category->name ?? null;
                    unset($product->category);

                    $product->is_discounted = $product->product_discount > 0;
                }
                return $items->values(); // reset indexes
            };

            $products = $processProducts($products);
            $foryouproducts = $processProducts($foryouproducts);

            return response()->json([
                'success'        => true,
                'message'        => 'Products fetched successfully',
                'products'       => $products,
                'foryouproducts' => $foryouproducts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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
                "product_images",
                "created_at",
                "updated_at"
            )
                ->where('store_id', '!=', 0)
                ->with(['category:id,name'])
                ->inRandomOrder();

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
                $product->product_rating = round(mt_rand(35, 50) / 10, 1);

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

            // Fetch product with store, category, and latest reviews
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
                'created_at',
                'product_category', // Category ID
                'product_subcategory' // Subcategory ID
            )
                ->with([
                    'store:store_id,store_profile_detail,store_info', // Fetch store details
                    'category:id,name', // Fetch category details
                    'reviews' => function ($q) {
                        $q->with('user:user_id,user_name')
                            ->latest(); // order reviews by created_at descending
                    }
                ])
                ->where('product_id', $product_id)
                ->where('store_id', '!=', 0)
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
                'product_date'             => $product->created_at,
                'category_id'              => $product->category->id ?? null,
                'category_name'            => $product->category->name ?? null,
                'review_count'             => $product->reviews->count(), // Count total reviews
                'store_id'                 => $product->store_id,
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
                    'review_id'    => $review->review_id,
                    'user_id'      => $review->user_id,
                    'username'     => $review->user->user_name ?? 'Unknown',
                    'rating'       => $review->rating,
                    'review'       => $review->review,
                    'images'       => json_decode($review->images, true), // ✅ Decode review images
                    'review_date'  => $review->created_at
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

    public function getWishlist(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        $wishlist = Wishlist::with(['product.category'])
            ->where('user_id', $user->user_id)
            ->get()
            ->map(function ($item) {
                if (!empty($item->product)) {
                    // Convert images to just the first one
                    $images = json_decode($item->product->product_images, true);
                    $item->product->product_image = $images[0] ?? null;
                    unset($item->product->product_images); // remove old key if needed

                    // Flatten category name
                    $item->product->category_name = $item->product->category->name ?? null;

                    // Remove the full category object
                    unset($item->product->category);
                }

                return $item;
            });


        if ($wishlist->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No wishlist items found',
                'wishlist' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'wishlist' => $wishlist,
        ]);
    }

    public function getCategories()
    {
        try {
            // Fetch all categories
            $categories = product_category::select('id', 'name', 'image', 'parent_id')->get();

            // Build tree starting from parent_id = null
            $categoryTree = $categories->where('parent_id', null)->map(function ($category) use ($categories) {
                return $this->buildCategoryNode($category, $categories);
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Categories fetched successfully',
                'data' => $categoryTree,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function buildCategoryNode($category, $allCategories)
    {
        $children = $allCategories->where('parent_id', $category->id)->map(function ($child) use ($allCategories) {
            return $this->buildCategoryNode($child, $allCategories);
        })->values();

        return [
            'id' => $category->id,
            'name' => $category->name,
            'image' => $category->image,
            'children' => $children
        ];
    }

    public function getSubCategories()
    {
        try {
            // Fetch all sub_categories fields from categories
            $categories = product_category::select('sub_categories')->get();

            $allSubCategories = [];

            foreach ($categories as $category) {
                $subCategories = json_decode($category->sub_categories, true);

                if (is_array($subCategories)) {
                    $allSubCategories = array_merge($allSubCategories, $subCategories);
                }
            }

            // Remove duplicates
            $allSubCategories = array_unique($allSubCategories, SORT_REGULAR);

            // Sort alphabetically
            usort($allSubCategories, function ($a, $b) {
                return strcmp(
                    is_array($a) ? ($a['name'] ?? '') : $a,
                    is_array($b) ? ($b['name'] ?? '') : $b
                );
            });

            return response()->json([
                'success' => true,
                'message' => 'Subcategories fetched successfully',
                'data' => array_values($allSubCategories),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function searchProducts(Request $request)
    {
        try {
            $query = $request->query('query');
            $categoryId = $request->query('category_id');

            $products = Products::select(
                'product_id',
                'product_name',
                'product_description',
                'product_brand',
                'product_category',
                'product_subcategory',
                'store_id',
                'product_price',
                'product_discount',
                'product_discounted_price',
                'product_images'
            )
                ->with(['category:id,name'])
                ->where('store_id', '!=', 0);

            // Apply filters
            $products->when($query, function ($q) use ($query) {
                $q->where(function ($q2) use ($query) {
                    $q2->where('product_name', 'LIKE', "%{$query}%")
                        ->orWhere('product_description', 'LIKE', "%{$query}%")
                        ->orWhere('product_brand', 'LIKE', "%{$query}%")
                        ->orWhere('product_subcategory', 'LIKE', "%{$query}%")
                        ->orWhereHas('category', function ($catQuery) use ($query) {
                            $catQuery->where('name', 'LIKE', "%{$query}%");
                        });
                });
            });

            $products->when($categoryId, function ($q) use ($categoryId) {
                $q->where('product_category', $categoryId);
            });

            $result = $products->limit(20)->get();

            // Post-processing
            foreach ($result as $product) {
                $product->product_images = json_decode($product->product_images, true);
                $product->product_image = $product->product_images[0] ?? null;
                unset($product->product_images);

                $product->product_rating = 4.5;
                $product->category_name = $product->category->name ?? null;
                unset($product->category);

                $product->is_discounted = $product->product_discount > 0;
            }

            return response()->json([
                'success' => true,
                'message' => 'Search results fetched successfully',
                'products' => $result
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getDashboardBanners()
    {
        try {
            $banners = DashboardBanner::select(
                'banner_id',
                'banner_title',
                'banner_image',
                'banner_link',
                'banner_status',
                'banner_sort_order'
            )
                ->where('banner_status', 1) // optional: only active banners
                ->orderBy('banner_sort_order', 'asc')
                ->get();
            if ($banners->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No banners found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dashboard banners fetched successfully',
                'data' => $banners,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}

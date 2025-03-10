<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use App\Models\Wishlist;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class apiproductController extends Controller
{
    public function getProducts()
    {
        try {
            $loggedInUser = Auth::user();

            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // Fetch products where user_id matches the logged-in user
            $products = Products::where('user_id', $loggedInUser->id)->get();

            // Decode JSON fields for better readability
            foreach ($products as $product) {
                $product->product_images = json_decode($product->product_images, true);
                $product->product_variation = json_decode($product->product_variation, true);
                $product->product_attributes = json_decode($product->product_attributes, true);
            }

            return response()->json(['success' => true, 'products' => $products], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
}

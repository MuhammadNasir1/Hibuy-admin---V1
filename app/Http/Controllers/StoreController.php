<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{

    public function getStoreDetails()
    {
        $userDetails = session('user_details');
        if (!$userDetails) {
            return redirect()->back()->with('error', 'User not authenticated');
        }

        $user_id = $userDetails['user_id'];

        // Find the seller record for the authenticated user
        $seller = Seller::where('user_id', $user_id)->first();
        if (!$seller) {
            return redirect()->back()->with('error', 'Seller record not found');
        }

        // Check if the store entry exists for the user and seller
        $store = Store::where('user_id', $user_id)
            ->where('seller_id', $seller->seller_id)
            ->first();

        // Determine store data and include appropriate IDs
        if ($store) {
            $storeData = !empty($store->store_profile_detail)
                ? json_decode($store->store_profile_detail, true)
                : json_decode($store->store_info, true);

            // Add `store_id` and `user_id`
            $storeData['store_id'] = $store->store_id;
            $storeData['user_id'] = $store->user_id;

            // Count the number of products for this store
            $storeData['product_count'] = Products::where('store_id', $store->store_id)->count();
        } else {
            $storeData = json_decode($seller->store_info, true);

            // Add `seller_id` and `user_id`
            $storeData['seller_id'] = $seller->seller_id;
            $storeData['user_id'] = $seller->user_id;

            // No product count needed since store doesn't exist
            $storeData['product_count'] = 0;
        }
        // return $storeData;
        // Pass data to the view
        return view('seller.store', compact('storeData'));
    }


    public function editStoreProfile(Request $request)
    {
        try {
            $userDetails = session('user_details');
            if (!$userDetails) {
                return redirect()->back()->with('error', 'User not authenticated');
            }

            $user_id = $userDetails['user_id'];

            // Find the seller record for the authenticated user
            $seller = Seller::where('user_id', $user_id)->first();
            if (!$seller) {
                return response()->json(['error' => 'Seller record not found'], 404);
            }

            // Decode store_info JSON from seller table
            $storeData = json_decode($seller->store_info, true);
            if (!$storeData) {
                return response()->json(['error' => 'Invalid store data'], 400);
            }

            // Check if the store entry exists for the user and seller
            $store = Store::where('user_id', $user_id)
                ->where('seller_id', $seller->seller_id)
                ->first();

            $isNew = false; // Flag to check if a new record is inserted

            // Get existing values
            $existingProfileDetail = $store ? json_decode($store->store_profile_detail, true) : [];
            $storeImagePath = $existingProfileDetail['store_image'] ?? null;
            $storeBannerPath = $existingProfileDetail['store_banner'] ?? null;
            $existingStoreTags = $existingProfileDetail['store_tags'] ?? [];
            $storePosts = $existingProfileDetail['store_posts'] ?? [];

            // Handle profile picture update only if provided
            if ($request->hasFile('profile_picture')) {
                $storeImagePath = 'storage/' . $request->file('profile_picture')->store('store_images', 'public');
            }

            // Handle banner update only if provided
            if ($request->hasFile('Banner')) {
                $storeBannerPath = 'storage/' . $request->file('Banner')->store('store_banners', 'public');
            }

            // Merge new tags with existing ones if provided
            $newTags = $request->input('tags', []);
            if (!is_array($newTags)) {
                $newTags = explode(',', $newTags);
            }
            $storeTags = array_unique(array_merge($existingStoreTags, $newTags));

            // Update store posts only if two new images are provided
            if ($request->hasFile('store_posts') && count($request->file('store_posts')) === 2) {
                $storePosts = []; // Reset store posts
                foreach ($request->file('store_posts') as $postImage) {
                    $postImagePath = 'storage/' . $postImage->store('store_posts', 'public');
                    $storePosts[] = ['image' => $postImagePath];
                }
            }

            // Store Profile Details
            $storeProfileDetail = [
                'store_name' => $request->input('store_name', $storeData['store_name'] ?? null),
                'store_image' => $storeImagePath,
                'store_tags' => $storeTags,
                'store_banner' => $storeBannerPath,
                'store_posts' => $storePosts,
            ];

            // If store exists, update it; otherwise, create a new store record
            if ($store) {
                $store->store_info = json_encode($storeData, JSON_UNESCAPED_UNICODE);
                $store->store_profile_detail = json_encode($storeProfileDetail, JSON_UNESCAPED_UNICODE);
                $store->save();
                $message = 'Store profile updated successfully';
            } else {
                $store = new Store();
                $store->user_id = $user_id;
                $store->seller_id = $seller->seller_id;
                $store->store_info = json_encode($storeData, JSON_UNESCAPED_UNICODE);
                $store->store_profile_detail = json_encode($storeProfileDetail, JSON_UNESCAPED_UNICODE);
                $store->save();
                $isNew = true;
                $message = 'Store profile created successfully';
            }

            return response()->json([
                'success' => true,
                'msg' => $message,
                'is_new' => $isNew,
                'data' => json_decode($store->store_profile_detail, true)
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function GetStoreInfo($store_id)
    {
        $userDetails = session('user_details');
        if (!$userDetails) {
            return redirect()->back()->with('error', 'User not authenticated');
        }

        $user_id = $userDetails['user_id'];
        // Find the seller record for the given store_id
        $seller = Seller::where('user_id', $user_id)->first();
        if (!$seller) {
            return response()->json(['error' => 'Seller record not found'], 404);
        }

        // Check if the store entry exists for the user and seller
        $store = Store::where('store_id', $store_id)
            ->where('seller_id', $seller->seller_id)
            ->first();

        // Determine store data and include appropriate IDs
        if ($store) {
            $storeData = !empty($store->store_profile_detail)
                ? json_decode($store->store_profile_detail, true)
                : json_decode($store->store_info, true);

            // Add store-specific details
            $storeData['store_id'] = $store->store_id;
            $storeData['user_id'] = $store->user_id;
            $storeData['product_count'] = Products::where('store_id', $store->store_id)->count();
        } else {
            $storeData = json_decode($seller->store_info, true);

            // Add seller-specific details
            $storeData['seller_id'] = $seller->seller_id;
            $storeData['user_id'] = $seller->user_id;
            $storeData['product_count'] = 0;
        }

        // Return JSON response
        return response()->json([
            'success' => true,
            'data' => $storeData
        ], 200);
    }
}

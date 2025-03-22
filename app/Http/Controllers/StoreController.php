<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function editStoreProfile(Request $request)
    {
        try {
            $user = Auth::user();

            // Find the seller record for the authenticated user
            $seller = Seller::where('user_id', $user->user_id)->first();
            if (!$seller) {
                return response()->json(['error' => 'Seller record not found'], 404);
            }

            // Decode store_info JSON from seller table
            $storeData = json_decode($seller->store_info, true);
            if (!$storeData) {
                return response()->json(['error' => 'Invalid store data'], 400);
            }

            // Check if the store entry exists for the user and seller
            $store = Store::where('user_id', $user->user_id)
                ->where('seller_id', $seller->seller_id)
                ->first();

            $isNew = false; // Flag to check if new record is inserted

            // Handle file uploads (store image, store banner)
            $storeImagePath = $store->store_image ?? null;
            $storeBannerPath = $store->store_banner ?? null;

            if ($request->hasFile('store_image')) {
                $storeImagePath = $request->file('store_image')->store('store_images', 'public');
            }

            if ($request->hasFile('store_banner')) {
                $storeBannerPath = $request->file('store_banner')->store('store_banners', 'public');
            }

            // Convert store tags from request to JSON
            $storeTags = $request->input('store_tags', []);
            if (!is_array($storeTags)) {
                $storeTags = explode(',', $storeTags); // If tags are sent as comma-separated string
            }

            // Handle store posts (with images)
            $storePosts = $store ? json_decode($store->store_profile_detail, true)['store_posts'] ?? [] : [];
            if ($request->has('store_posts')) {
                foreach ($request->file('store_posts', []) as $index => $postImage) {
                    // Validate that only 2 posts are stored
                    if ($index >= 2) break;

                    $postImagePath = $postImage->store('store_posts', 'public');
                    $storePosts[] = [
                        'image' => $postImagePath,
                    ];
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
                $store->user_id = $user->user_id;
                $store->seller_id = $seller->seller_id;
                $store->store_info = json_encode($storeData, JSON_UNESCAPED_UNICODE);
                $store->store_profile_detail = json_encode($storeProfileDetail, JSON_UNESCAPED_UNICODE);
                $store->save();
                $isNew = true; // Set flag as true if inserted
                $message = 'Store profile created successfully';
            }

            return response()->json([
                'success' => true,
                'msg' => $message,
                'is_new' => $isNew, // Indicate if new record was inserted
                'data' => json_decode($store->store_profile_detail, true) // Decode JSON to array
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function GetStoreInfo($user_id)
    {
        // Find the seller record for the authenticated user
        $seller = Seller::where('user_id', $user_id)->first();
        if (!$seller) {
            return response()->json(['error' => 'Seller record not found'], 404);
        }

        // Check if the store entry exists for the user and seller
        $store = Store::where('user_id', $user_id)
            ->where('seller_id', $seller->seller_id)
            ->first();

        if ($store) {
            // Check if store_profile_detail is not empty or null
            if (!empty($store->store_profile_detail)) {
                return response()->json([
                    'store_profile_detail' => json_decode($store->store_profile_detail, true)
                ], 200);
            } else {
                return response()->json([
                    'store_info' => json_decode($store->store_info, true)
                ], 200);
            }
        } else {
            return response()->json([
                'store_info' => json_decode($seller->store_info, true)
            ], 200);
        }
    }
}

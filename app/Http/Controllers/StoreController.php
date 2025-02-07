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

            // Handle file uploads (store image, store banner)
            $storeImagePath = null;
            $storeBannerPath = null;

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
            $storePosts = [];
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

            // Create store entry
            $store = new Store();
            $store->user_id = $user->user_id;
            $store->seller_id = $seller->seller_id;
            $store->store_info = json_encode($storeData, JSON_UNESCAPED_UNICODE);

            // Store Profile Details
            $storeProfileDetail = [
                'store_name' => $request->input('store_name', $storeData['store_name'] ?? null),
                'store_image' => $storeImagePath,
                'store_tags' => $storeTags,
                'store_banner' => $storeBannerPath,
                'store_posts' => $storePosts,
            ];

            // Store store profile details as JSON
            $store->store_profile_detail = json_encode($storeProfileDetail, JSON_UNESCAPED_UNICODE);
            $store->save();

            return response()->json([
                'success' => true,
                'msg' => 'Store profile created successfully',
                'data' => $store
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}

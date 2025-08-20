<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Seller;
use App\Models\Products;
use App\Models\Query;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        return view('seller.Store', compact('storeData'));
    }


   public function editStoreProfile(Request $request)
{
    try {
        $userDetails = session('user_details');
        if (!$userDetails) {
            return redirect()->back()->with('error', 'User not authenticated');
        }

        $user_id = $userDetails['user_id'];

        // Find seller record
        $seller = Seller::where('user_id', $user_id)->first();
        if (!$seller) {
            return response()->json(['error' => 'Seller record not found'], 404);
        }

        // Decode seller store_info
        $sellerStoreData = json_decode($seller->store_info, true);
        if (!$sellerStoreData) {
            return response()->json(['error' => 'Invalid store data'], 400);
        }

        // Find store
        $store = Store::where('user_id', $user_id)
            ->where('seller_id', $seller->seller_id)
            ->first();

        $isNew = false;
        $existingProfileDetail = $store ? json_decode($store->store_profile_detail, true) : [];

        // Existing details
        $storeImagePath   = $existingProfileDetail['store_image']   ?? null;
        $existingStoreTags = $existingProfileDetail['store_tags']   ?? [];
        $storePosts       = $existingProfileDetail['store_posts']   ?? [];
        $existingBanners  = $existingProfileDetail['store_banners'] ?? [];

        // Profile picture
        if ($request->hasFile('profile_picture')) {
            $storeImagePath = 'storage/' . $request->file('profile_picture')->store('store_images', 'public');
        }

        // Remove banners
        $removedBannerIds = $request->input('removed_banners', []);
        if (!is_array($removedBannerIds)) {
            $removedBannerIds = json_decode($removedBannerIds, true) ?: [];
        }
        if (!empty($removedBannerIds)) {
            $existingBanners = array_values(array_filter($existingBanners, function ($banner) use ($removedBannerIds) {
                return !in_array($banner['id'], $removedBannerIds);
            }));
        }

        // New banners
        if ($request->hasFile('banners')) {
            $currentMaxId = !empty($existingBanners) ? max(array_column($existingBanners, 'id')) : 0;
            foreach ($request->file('banners') as $index => $bannerFile) {
                $path = 'storage/' . $bannerFile->store('store_banners', 'public');
                $existingBanners[] = [
                    'id' => $currentMaxId + $index + 1,
                    'image' => $path
                ];
            }
        }

        // Tags
        $newTags = $request->input('tags', []);
        if (!is_array($newTags)) {
            $newTags = explode(',', $newTags);
        }
        $storeTags = array_unique(array_merge($existingStoreTags, $newTags));

        // Store posts (only if exactly 2 new ones provided)
        if ($request->hasFile('store_posts') && count($request->file('store_posts')) === 2) {
            $storePosts = [];
            foreach ($request->file('store_posts') as $postImage) {
                $postImagePath = 'storage/' . $postImage->store('store_posts', 'public');
                $storePosts[] = ['image' => $postImagePath];
            }
        }

        // ✅ Always update store_name across all JSON
        $newStoreName = $request->input('store_name', $sellerStoreData['store_name'] ?? null);

        // Update seller.store_info
        $sellerStoreData['store_name'] = $newStoreName;
        $seller->store_info = json_encode($sellerStoreData, JSON_UNESCAPED_UNICODE);
        $seller->save();

        // Prepare store profile detail
        $storeProfileDetail = [
            'store_name'    => $newStoreName,
            'store_image'   => $storeImagePath,
            'store_tags'    => $storeTags,
            'store_banners' => $existingBanners,
            'store_posts'   => $storePosts,
        ];

        // If store exists, update; otherwise create
        if ($store) {
            $storeData = json_decode($store->store_info, true) ?? [];
            $storeData['store_name'] = $newStoreName;

            $store->store_info = json_encode($storeData, JSON_UNESCAPED_UNICODE);
            $store->store_profile_detail = json_encode($storeProfileDetail, JSON_UNESCAPED_UNICODE);
            $store->save();

            $message = 'Store profile updated successfully';
        } else {
            $store = new Store();
            $store->user_id = $user_id;
            $store->seller_id = $seller->seller_id;

            $storeData = $sellerStoreData; // use updated seller data
            $storeData['store_name'] = $newStoreName;

            $store->store_info = json_encode($storeData, JSON_UNESCAPED_UNICODE);
            $store->store_profile_detail = json_encode($storeProfileDetail, JSON_UNESCAPED_UNICODE);
            $store->save();

            $isNew = true;
            $message = 'Store profile created successfully';
        }

        return response()->json([
            'success' => true,
            'msg'     => $message,
            'is_new'  => $isNew,
            'data'    => json_decode($store->store_profile_detail, true)
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

    public function getQuery()
    {
        $user = session('user_details')['user_id'];
        $queries = Query::where('user_id', $user)->get()->map(function ($query) {
            $query->submission_date = Carbon::parse($query->updated_at)->format('d F, Y');
            return $query;
        });

        return view('pages.Queries', compact('queries'));
    }

    public function updateQuery(Request $request, string $id)
    {
        try {

            $request->validate([
                'status' => 'required',
                'response' => 'required',
            ]);

            $get_query = Query::where('query_id', $id)->first();

            $get_query->status = $request->status;
            $get_query->response = $request->response;
            $get_query->save();

            return response()->json(['success' => true, 'message' => 'Query Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }


}

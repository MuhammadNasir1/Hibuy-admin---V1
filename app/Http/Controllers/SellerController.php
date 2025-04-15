<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function insertDetails(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,user_id',
                'info_type' => 'required|string',
                'data' => 'required|json',
            ]);
            // $newData = $validatedData['info_type'];

            $seller = Seller::where('user_id', $validatedData['user_id'] )->first();
            if (!$seller) {
                return response()->json(['success' => false, 'message' => "Seller Not Found"], 404);
            }

            $seller->$validatedData['info_type'] = json_encode($validatedData['data']);

            return response()->json(['success' => true, 'message' => "Data Inserted Successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }


    public function getSellerDetail($sellerId)
    {
        try {
            $store = Store::select('store_id', 'store_profile_detail', 'store_info')
                ->where('seller_id', $sellerId)
                ->first();

            $userDetails = session('user_details');
            if (!$userDetails) {
                return redirect()->back()->with('error', 'User not authenticated');
            }

            $user_id = $userDetails['user_id'];

            // Always get seller info with user
            $seller = Seller::with('user')
                ->select('seller_id', 'store_info', 'personal_info', 'user_id') // include user_id here!
                ->where('seller_id', $sellerId)
                ->first();

            if (!$seller) {
                return redirect()->back()->with('error', 'Seller not found');
            }

            // Decode and filter personal info
            $personalInfo = json_decode($seller->personal_info, true);
            $personalInfoFiltered = [
                'full_name' => $personalInfo['full_name'] ?? null,
                'profile_picture' => $personalInfo['profile_picture'] ?? null,
                'phone_no' => $personalInfo['phone_no'] ?? null,
                'email' => $personalInfo['email'] ?? null,
            ];

            // If store exists
            if ($store) {
                $storeProfileDetail = json_decode($store->store_profile_detail, true);
                $storeInfo = json_decode($store->store_info, true);

                $storeData = !empty($storeProfileDetail) ? $storeProfileDetail : $storeInfo;

                // Get products
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
                    "product_images",
                    "is_boosted",
                    "product_status"
                )
                    ->where('store_id', $store->store_id)
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

                // Final response
                $storeData['products'] = $products;
                $storeData['personal_info'] = $personalInfoFiltered;
                $storeData['seller'] = $seller;

                // return $storeData;
                if ($seller->user?->user_role === 'seller') {
                    return view('admin.SellerProfile', compact('storeData'));
                } else {
                    return view('admin.FreelancerProfile', compact('storeData'));
                }
                // return view('admin.SellerProfile', compact('storeData'));
            } else {
                // Fallback if store doesn't exist
                $storeData = json_decode($seller->store_info, true);
                $storeData['products'] = [];
                $storeData['personal_info'] = $personalInfoFiltered;
                $storeData['seller'] = $seller;

                // return view('admin.SellerProfile', compact('storeData'));
                if ($seller->user?->user_role === 'seller') {
                    return view('admin.SellerProfile', compact('storeData'));
                } else {
                    return view('admin.FreelancerProfile', compact('storeData'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Inquiry;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

            $seller = Seller::where('user_id', $validatedData['user_id'])->first();
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

    public function saveTransactionImage(Request $request)
    {
        $request->validate([
            'transaction_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userId = session('user_details.user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Decode existing JSON if it exists
        $packageDetail = json_decode($user->package_detail, true) ?? [];

        // Delete old image if it exists
        if (!empty($packageDetail['transaction_image']) && Storage::disk('public')->exists($packageDetail['transaction_image'])) {
            Storage::disk('public')->delete($packageDetail['transaction_image']);
        }

        // Store new image
        $path = $request->file('transaction_image')->store('transactions', 'public');

        // Update package_detail as JSON
        $packageDetail['package_type'] = 'silver';
        $packageDetail['transaction_image'] = $path;
        $packageDetail['package_status'] = 'pending';

        $user->package_detail = json_encode($packageDetail);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Image submitted successfully.'
        ]);
    }

    public function showPromotions()
    {
        $users = User::whereNotNull('package_detail->transaction_image')->get();
        // return $users;
        return view('admin.Promotions', compact('users'));
    }

    public function updatePackageStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'package_status' => 'required|in:approved,rejected,pending'
        ]);

        $user = User::where('user_id', $request->user_id)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // Decode package_detail JSON
        $packageDetail = json_decode($user->package_detail, true) ?? [];


        $packageDetail['package_status'] = $request->package_status;


        if ($request->package_status === 'approved') {
            $packageDetail['package_start_date'] = now()->toDateString();
            $packageDetail['package_end_date'] = now()->addMonth()->toDateString();
        } else {
            $packageDetail['package_start_date'] = null;
            $packageDetail['package_end_date'] = null;

            Products::where('user_id', $user->user_id)
                ->where('is_boosted', 1)
                ->update([
                    'is_boosted' => 0,
                    'boost_start_date' => null,
                    'boost_end_date' => null
                ]);
        }

        $user->package_detail = json_encode($packageDetail);
        $user->save();

        return response()->json(['success' => true]);
    }


    public function BoostStatus()
    {
        $userId = session('user_details.user_id');

        $user = User::where('user_id', $userId)->first();

        // Decode the JSON package_detail field
        $packageDetail = json_decode($user->package_detail, true) ?? [];

        $packageStatus = $packageDetail['package_status'] ?? null;

        return view('seller.BoostProducts', compact('user', 'packageStatus', 'packageDetail'));
    }


    public function store(Request $request)
    {
        $buyerId = session('user_details.user_id');


        if (!$buyerId) {
            return response()->json(['success' => false, 'message' => 'User not logged in.'], 401);
        }
        $validated = $request->validate([
            'product_id' => 'required',
            'seller_userid' => 'required',
            'category_id' => 'required',
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'twenty_percent_amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        Inquiry::create([
            'buyer_id' => $buyerId,
            'seller_id' => $validated['seller_userid'],
            'product_id' => $validated['product_id'],
            'product_category' => $validated['category_id'],
            'product_stock' => $validated['stock'],
            'amount' => $validated['price'] * $validated['stock'],
            'twenty_percent_amount' => $validated['twenty_percent_amount'],
            'remaining_amount' => ($validated['price'] * $validated['stock']) - $validated['twenty_percent_amount'],
            'inquiry_date' => now(),
            'status' => 'pending',
            'note' => $validated['note'],
        ]);

        return response()->json(['success' => true]);
    }

    public function purchases()
    {
        $buyerId = session('user_details.user_id');

        $inquiries = Inquiry::where('buyer_id', $buyerId)
            ->join('products', 'inquiries.product_id', '=', 'products.product_id')
            ->join('categories', 'inquiries.product_category', '=', 'categories.id')
            ->join(
                'users',
                'inquiries.seller_id',
                '=',
                'users.user_id'
            )
            ->select(
                'inquiries.*',
                'products.product_name',
                'products.product_images',
                'categories.name as category_name',
                'users.user_name as seller_name'
            )
            ->latest()
            ->get();
        // return $inquiries;
        return view('seller.Purchases', compact('inquiries'));
    }


    public function saveInquiryImage(Request $request)
    {
        try {
            $request->validate([
                'transaction_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'inquiry_id' => 'required|exists:inquiries,inquiry_id',
            ]);

            if ($request->hasFile('transaction_image')) {
                $path = $request->file('transaction_image')->store('inquiries', 'public');

                Inquiry::where('inquiry_id', $request->inquiry_id)->update([
                    'payment_ss' => $path
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'path' => $path
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image provided'
            ], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }



    public function purchasesWithProductDetails($inquiryId)
    {
        $inquiry = Inquiry::where('inquiry_id', $inquiryId)
            ->join('products', 'inquiries.product_id', '=', 'products.product_id')
            ->join('categories', 'inquiries.product_category', '=', 'categories.id')
            ->join('users', 'inquiries.seller_id', '=', 'users.user_id')
            ->select(
                'inquiries.*',
                'products.product_name',
                'products.product_description',
                'products.product_brand',
                'products.product_price',
                'products.product_discount',
                'products.product_discounted_price',
                'products.product_variation',
                'products.product_images',
                'categories.name as category_name',
                'users.user_name as seller_name'
            )
            ->first();

        if (!$inquiry) {
            return response()->json(['success' => false, 'message' => 'Inquiry not found'], 404);
        }

        // Decode JSON fields
        $inquiry->product_images = json_decode($inquiry->product_images, true);
        $inquiry->product_variation = json_decode($inquiry->product_variation, true);
        // return $inquiry;
        return response()->json(['success' => true, 'data' => $inquiry]);
    }


    public function inquiries()
    {
        $sellerId = session('user_details.user_id');

        $inquiries = Inquiry::where('seller_id', $sellerId)
            ->join('products', 'inquiries.product_id', '=', 'products.product_id')
            ->join('categories', 'inquiries.product_category', '=', 'categories.id')
            ->join('users', 'inquiries.buyer_id', '=', 'users.user_id')
            ->select(
                'inquiries.*',
                'products.product_name',
                'products.product_images',
                'categories.name as category_name',
                'users.user_name as buyer_name'
            )
            ->latest()
            ->get();
        // return $inquiries;
        return view('seller.Inquiries', compact('inquiries'));
    }



    public function updateInquiryStatus(Request $request)
    {
        try {
            $request->validate([
                'inquiry_id' => 'required|exists:inquiries,inquiry_id',
                'status' => 'required|in:pending,approved,rejected',
            ]);

            $inquiry = Inquiry::where('inquiry_id', $request->inquiry_id)->first();
            $product_id = $inquiry->product_id;
            $product = Products::where('product_id', $product_id)->first();
            $productData = [
                'product_name' => $product->product_name,
                'product_price' => $product->product_price,
                'product_discounted_price' => $product->product_discounted_price,
                'product_images' => json_decode($product->product_images, true),
                'product_description' => $product->product_description,
                'product_brand' => $product->product_brand,
                'product_category' => $product->product_category,
                'product_subcategory' => $product->product_subcategory,
                'product_variation' => json_decode($product->product_variation, true),
                'seller_id' => $inquiry->seller_id,
            ];

            $getsellerStoreId = Store::where('user_id', $inquiry->buyer_id)->first();
            if (!$getsellerStoreId) {
                return response()->json(['success' => false, 'message' => 'Seller store not found'], 404);
            }
            $storeData = new Products();
            $storeData->product_name = $productData['product_name'];
            $storeData->product_price = $productData['product_price'];
            $storeData->product_discounted_price = 0;
            $storeData->product_images = json_encode($productData['product_images']);
            $storeData->product_description = $productData['product_description'];
            $storeData->product_brand = $productData['product_brand'];
            $storeData->product_category = $productData['product_category'];
            $storeData->product_subcategory = $productData['product_subcategory'];
            $storeData->store_id = $getsellerStoreId->store_id ?? 0;
            $storeData->user_id = $inquiry->buyer_id;
            $storeData->is_boosted = 0;
            $storeData->boost_start_date = null;
            $storeData->boost_end_date = null;
            $storeData->purchase_price = 0;
            $storeData->product_discount =  0;
            $storeData->product_status = 0; // Set default status to active
            $storeData->save();

            $inquiry->status = $request->status;
            $inquiry->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_merge(...array_values($e->errors())))
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}

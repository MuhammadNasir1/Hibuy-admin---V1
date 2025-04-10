<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\Products;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function profileDetail()
    {
        $user = session('user_details')['user_id'];
        $seller = Seller::Where('user_id', $user)->first();

        $statusImages = [
            'personal_info' => asset('asset/kyc-pending.png'),
            'store_info' => asset('asset/kyc-pending.png'),
            'documents_info' => asset('asset/kyc-pending.png'),
            'bank_info' => asset('asset/kyc-pending.png'),
            'business_info' => asset('asset/kyc-pending.png'),
        ];

        $imageMap = [
            'pending' => asset('asset/kyc-pending.png'),
            'approved' => asset('asset/kyc-approve.png'),
            'rejected' => asset('asset/kyc-reject.png'),
        ];

        $jsonColumns = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];
        foreach ($jsonColumns as $column) {
            if (!empty($seller->$column)) {
                $jsonData = json_decode($seller->$column, true);
                if (!empty($jsonData['status']) && isset($imageMap[$jsonData['status']])) {
                    $statusImages[$column] = $imageMap[$jsonData['status']];
                }
            }
        }
        return view('Auth.ProfileDetail', compact('seller', 'statusImages'));
        // return response()->json(['success' => true, 'data' => $seller]);
    }

    public function setPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'password' => 'required|confirmed',
            ]);

            // Decode the user_id
            $decryptId = Crypt::decrypt($validatedData['user_id']);
            // Find user by user_id instead of id
            $user = User::where('user_id', $decryptId)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Hash the password before saving
            $user->user_password = Hash::make($validatedData['password']);
            $user->save();

            return response()->json(['success' => true, 'message' => 'Password has been updated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }


    public function KYC_Authentication(Request $request)
    {
        try {
            $user = session('user_details')['user_id'];

            // Find the seller record for the authenticated user
            $seller = Seller::where('user_id', $user)->first();
            if (!$seller) {
                return response()->json(['error' => 'Seller record not found'], 404);
            }

            // Define step mapping
            $stepMapping = [
                1 => 'personal_info',
                2 => 'store_info',
                3 => 'documents_info',
                4 => 'bank_info',
                5 => 'business_info',
            ];

            // Validate request input
            $validatedData = $request->validate([
                'step' => 'required|integer|between:1,5',
            ]);

            $step = $validatedData['step'];

            // Verify step exists in mapping
            if (!isset($stepMapping[$step])) {
                return response()->json(['error' => 'Invalid step'], 400);
            }

            // Get the column name based on step
            $column = $stepMapping[$step];

            // Separate text and file data
            $textData = $request->except(['_token']);
            $fileData = [];

            // Define custom upload path
            $uploadPath = public_path('storage/kyc_files');

            // Ensure the directory exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Handle file uploads
            foreach ($request->allFiles() as $fileField => $file) { // Iterate through all uploaded files
                if ($request->hasFile($fileField)) {
                    $fileName = time() . '_' . $fileField . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fileName);
                    $fileData[$fileField] = 'storage/kyc_files/' . $fileName; // Store relative path
                }
            }

            // Merge text and file data
            $finalData = array_merge($textData, $fileData);

            // Encode to JSON safely
            $jsonData = json_encode($finalData, JSON_UNESCAPED_UNICODE);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Store in DB
            $seller->$column = $jsonData;
            $seller->save();

            return response()->json([
                'success' => ucfirst(str_replace('_', ' ', $column)) . ' updated successfully',
                'data' => $finalData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function sellerManagement()
    {
        $userDetails = session('user_details');
        $user_id = $userDetails['user_id'] ?? null;
        $role = $userDetails['user_role'] ?? null;

        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $sellers = Seller::with('user')
            ->whereHas('user', function ($query) {
                $query->where('user_role', 'seller')
                    ->where('status', "approved");
            })
            ->get()
            ->map(function ($query) {
                $query->submission_date = Carbon::parse($query->updated_at)->format('d F, Y');
                return $query;
            });

        return view('admin.SellerManagement', compact('sellers'));
    }

    public function freelancerManagement()
    {
        $userDetails = session('user_details');
        $user_id = $userDetails['user_id'] ?? null;
        $role = $userDetails['user_role'] ?? null;

        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $sellers = Seller::with('user')
            ->whereHas('user', function ($query) {
                $query->where('user_role', 'freelancer')
                    ->where('status', "approved");
            })
            ->get()
            ->map(function ($query) {
                $query->submission_date = Carbon::parse($query->updated_at)->format('d F, Y');
                return $query;
            });
        //  return $sellers;
        return view('admin.FreelancersManagement', compact('sellers'));
    }

    public function getSellerData(string $id)
    {
        $user_id = base64_decode($id);

        $seller = Seller::where('user_id', $user_id)->first();
        if (!$seller) {
            return response()->json(['error' => 'Seller record not found'], 404);
        }

        $store = Store::where('seller_id', $seller->seller_id)
            ->with('users') // Fetch related user data
            ->first();

        if ($store) {
            $storeData = !empty($store->store_profile_detail)
                ? json_decode($store->store_profile_detail, true)
                : json_decode($store->store_info, true);

            $storeData['store_id'] = $store->store_id;
            $storeData['user_id'] = $store->user_id;
            $storeData['product_count'] = Products::where('store_id', $store->store_id)->count();
            $storeData['user'] = $store->users; // Include user data
        } else {
            $storeData = json_decode($seller->store_info, true);

            $storeData['seller_id'] = $seller->seller_id;
            $storeData['user_id'] = $seller->user_id;
            $storeData['product_count'] = 0;
            $storeData['user'] = User::find($seller->user_id); // Fetch user data directly if no store
        }

        // Personal Info JSON se personal_profile_picture nikalna
        $personalInfo = json_decode($seller->personal_info, true);
        $storeData['profile_picture'] = $personalInfo['profile_picture'] ?? null;

        return view('admin.FreelancerProfile', compact('storeData'));
    }

    public function getBuyerData()
    {
        $buyers = Customer::with('user')
            ->whereHas('user', function ($query) {
                $query->where('user_role', 'customer');
            })
            ->get();

        $userIds = $buyers->pluck('user_id')->filter()->unique()->map(function ($id) {
            return (int) $id;
        })->toArray();

        $orders = DB::table('orders')
            ->whereIn('user_id', $userIds)
            ->select('user_id', 'total')
            ->get()
            ->groupBy('user_id', function ($order) {
                return  $order->user_id;
            });

        $buyers = $buyers->map(function ($buyer) use ($orders) {

            $userOrders = $orders->get((int) $buyer->user_id, collect());

            $customerData = $buyer->toArray();

            $customerData['order_count'] = $userOrders->count();
            $customerData['total_spend'] = $userOrders->sum('total') ?? 0;


            if ($buyer->user) {
                $customerData['user']['joined_date'] = $buyer->user->created_at->format('d F, Y');
            }

            return $customerData;
        });


        // return response()->json($buyers);
        return view('admin.BuyersManagement', compact('buyers'));
    }

    public function getBuyerDetails(string $id)
    {
        $user_id = base64_decode($id);

        $buyer = Customer::where('user_id', $user_id)
            ->with('user')
            ->first();

        if (!$buyer) {
            return response()->json(['error' => 'Buyer record not found'], 404);
        }

        $orders = DB::table('orders')
            ->where('user_id', $user_id)
            ->select('order_id', 'total', 'created_at')
            ->get();

        $queries = DB::table('queries')
            ->where('user_id', $user_id)
            ->get();

        $buyer->orders = $orders;
        $buyer->queries = $queries;

        // return $buyer;
        return view('admin.BuyerProfile', compact('buyer'));
    }
}

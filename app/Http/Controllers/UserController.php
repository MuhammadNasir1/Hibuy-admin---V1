<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\User;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\product_category;
use App\Models\Query;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function showUsers()
    {
        $userDetails = session('user_details');

        $role = $userDetails['user_role'] ?? null;
        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Fetch all users except the admin
        $users = User::where('user_role', '!=', 'admin')->get();

        // dd($users);

        return view('admin.Users', compact('users'));
    }

    public function profileDetail()
    {
        $user = session('user_details')['user_id'];
        $seller = Seller::Where('user_id', $user)->first();

        // Get user name from users table
        $user = DB::table('users')->where('user_id', $user)->first();

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
        return view('Auth.ProfileDetail', compact('seller', 'statusImages', 'user'));
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
            $seller = Seller::where('user_id', $user)->first();

            if (!$seller) {
                return response()->json(['success' => false, 'message' => 'Seller record not found'], 404);
            }

            $stepMapping = [
                1 => 'personal_info',
                2 => 'store_info',
                3 => 'documents_info',
                4 => 'bank_info',
                5 => 'business_info',
            ];

            $validator = Validator::make($request->all(), [
                'step' => 'required|integer|between:1,5',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $step = $request->input('step');

            if (!isset($stepMapping[$step])) {
                return response()->json(['success' => false, 'message' => 'Invalid step'], 400);
            }

            $column = $stepMapping[$step];

            $stepRules = [
                1 => [
                    'status' => 'required',
                    'full_name' => 'required|string|max:255',
                    'address' => 'required|string|max:500',
                    'phone_no' => 'required|regex:/^[0-9]+$/|string|min:8|max:15',
                    'email' => 'required|email|max:255',
                    'cnic' => 'required|string|max:15',

                    'profile_picture' => 'required_without:profile_picture_path|file|mimes:jpg,jpeg,png|max:2048',
                    'profile_picture_path' => 'nullable|string',

                    'front_image' => 'required_without:front_image_path|file|mimes:jpg,jpeg,png|max:2048',
                    'front_image_path' => 'nullable|string',

                    'back_image' => 'required_without:back_image_path|file|mimes:jpg,jpeg,png|max:2048',
                    'back_image_path' => 'nullable|string',
                ],
                2 => [
                    'status' => 'required|in:pending',
                    'store_name' => 'required|string|max:255',
                    'type' => 'required|in:Retail,Wholesale',
                    'phone_no' => 'required|regex:/^[0-9]+$/|string|min:8|max:15',
                    'email' => 'required|email|max:255',
                    'country' => 'required|string|max:100',
                    'province' => 'required|string|max:100',
                    'city' => 'required|string|max:100',
                    'zip_code' => 'required|string|max:10',
                    'address' => 'required|string|max:500',
                    'pin_location' => [
                        'required',
                        'string',
                        'max:255',
                        'regex:/^-?\d{1,2}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$/',
                    ],

                    'profile_picture_store' => 'required_without:profile_picture_store_path|file|mimes:jpg,jpeg,png|max:2048',
                    'profile_picture_store_path' => 'nullable|string',
                ],
                3 => [
                    'status' => 'required|in:pending',
                    'country' => 'required|string|max:100',
                    'province' => 'required|string|max:100',
                    'city' => 'required|string|max:100',

                    'home_bill' => 'required_without:home_bill_path|file|mimes:jpg,jpeg,png|max:2048',
                    'home_bill_path' => 'nullable|string',

                    'shop_video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480',
                    'shop_video_path' => 'nullable|string',
                ],
                4 => [
                    'status' => 'required|in:pending',
                    'account_type' => 'required|in:savings,current',
                    'bank_name' => 'required|string|max:255',
                    'branch_code' => 'required|string|max:50',
                    'branch_name' => 'required|string|max:255',
                    'branch_phone' => 'required|regex:/^[0-9]+$/|string|min:8|max:15',
                    'account_title' => 'required|string|max:255',
                    'account_no' => 'required|string|max:50',
                    'iban_no' => 'required|string|max:50',

                    'canceled_cheque' => 'required_without:canceled_cheque_path|file|mimes:jpg,jpeg,png|max:2048',
                    'canceled_cheque_path' => 'nullable|string',

                    'verification_letter' => 'nullable:verification_letter_path|file|mimes:jpg,jpeg,png|max:2048',
                    'verification_letter_path' => 'nullable|string',
                ],
                5 => [
                    'status' => 'required|in:pending',
                    'business_name' => 'required|string|max:255',
                    'owner_name' => 'required|string|max:255',
                    'phone_no' => 'required|regex:/^[0-9]+$/|string|min:8|max:15',
                    'reg_no' => 'required|string|max:100',
                    'tax_no' => 'required|string|max:100',
                    'address' => 'required|string|max:500',
                    'pin_location' => [
                        'required',
                        'string',
                        'max:255',
                        'regex:/^-?\d{1,2}(\.\d+)?,\s*-?\d{1,3}(\.\d+)?$/',
                    ],

                    'personal_profile' => 'required_without:personal_profile_path|file|mimes:jpg,jpeg,png|max:2048',
                    'personal_profile_path' => 'nullable|string',

                    'letter_head' => 'required_without:letter_head_path|file|mimes:jpg,jpeg,png|max:2048',
                    'letter_head_path' => 'nullable|string',

                    'stamp' => 'required_without:stamp_path|file|mimes:jpg,jpeg,png|max:2048',
                    'stamp_path' => 'nullable|string',
                ],
            ];

            // Step-specific validation
            $stepValidator = Validator::make($request->all(), $stepRules[$step], [
                'personal_profile.required_without' => 'The Personal Profile is required.',
                'letter_head.required_without' => 'The Letter Head is required.',
                'stamp.required_without' => 'The Stamp is required.',
                'profile_picture.required_without' => 'The Profile Picture is required.',
                'front_image.required_without' => 'The Front Image is required.',
                'back_image.required_without' => 'The Back Image is required.',
                'profile_picture_store.required_without' => 'The Store Profile Picture is required.',
                'home_bill.required_without' => 'The Home Bill is required.',
                'canceled_cheque.required_without' => 'The Canceled Cheque is required.',
                'verification_letter.required_without' => 'The Verification Letter is required.',

                'pin_location.regex' => 'Pin location must be a valid "latitude, longitude" coordinate pair.',
            ]);

            if ($stepValidator->fails()) {
                return response()->json(['success' => false, 'errors' => $stepValidator->errors()], 422);
            }

            $existingData = [];
            if (!empty($seller->$column)) {
                $decoded = json_decode($seller->$column, true);
                if (is_array($decoded)) {
                    $existingData = $decoded;
                }
            }

            $textData = $request->except(array_keys($request->allFiles()));
            $fileData = [];

            $uploadPath = public_path('storage/kyc_files');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($request->allFiles() as $fileField => $file) {
                if ($request->hasFile($fileField)) {
                    $fileName = time() . '_' . $fileField . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fileName);
                    $fileData[$fileField] = 'storage/kyc_files/' . $fileName;
                }
            }

            foreach (array_keys($stepRules[$step]) as $field) {
                if (str_ends_with($field, '_path')) {
                    $originalField = str_replace('_path', '', $field);
                    if (!isset($fileData[$originalField]) && $request->filled($field)) {
                        $fileData[$originalField] = $request->input($field);
                    }
                }
            }

            $finalData = array_merge($existingData, $textData, $fileData);
            $jsonData = json_encode($finalData, JSON_UNESCAPED_UNICODE);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['success' => false, 'message' => 'Invalid JSON data'], 400);
            }

            $seller->$column = $jsonData;
            $seller->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $column)) . ' updated successfully',
                'data' => $finalData
            ]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
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


    public function viewFaqCategory()
    {
        $userDetails = session('user_details');
        $user_id = $userDetails['user_id'] ?? null;
        $role = $userDetails['user_role'] ?? null;

        if ($role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        $categories = product_category::where('category_type', 'faqs')->get();

        return response()->json([
            'success' => true,
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }

    public function storeFaqs()
    {
        $userDetails = session('user_details');
        $role = $userDetails['user_role'] ?? null;

        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $validated = request()->validate([
            'faq_id' => 'nullable|exists:faqs,faq_id',
            'faq_question' => 'required|string|max:255',
            'faq_answer' => 'required|string',
            'faq_category_id' => 'required|exists:categories,id',
            'status' => 'required|boolean',
        ]);

        // Check if category type is valid
        $category = product_category::find($validated['faq_category_id']);
        if (!$category || $category->category_type !== 'faqs') {
            return response()->json(['success' => false, 'message' => 'Invalid category'], 400);
        }

        if (!empty($validated['faq_id'])) {
            // Update
            $faq = Faq::find($validated['faq_id']);
            if ($faq) {
                $faq->update([
                    'question' => $validated['faq_question'],
                    'answer' => $validated['faq_answer'],
                    'faq_category' => $validated['faq_category_id'],
                    'is_active' => $validated['status'],
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'FAQ updated successfully',
                    'data' => $faq
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'FAQ not found'], 404);
            }
        } else {
            // Create
            $faq = Faq::create([
                'question' => $validated['faq_question'],
                'answer' => $validated['faq_answer'],
                'faq_category' => $validated['faq_category_id'],
                'is_active' => $validated['status'],
            ]);
            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully',
                'data' => $faq
            ]);
        }
    }




    public function HelpCenter()
    {
        $userDetails = session('user_details');
        $role = $userDetails['user_role'] ?? null;

        if ($role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // get all FAQs with related category's name and image
        $faqs = Faq::with(['category:id,name,image'])->get();

        $faqsCategories = product_category::select(
            'id',
            'name',
            'image'
        )
            ->where('category_type', 'faqs')->get();

        return view('admin.HelpCenter', compact('faqs', 'faqsCategories'));
    }


    public function storeFaqCategory(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_image' => 'required|image|max:2048',
            'Categorystatus' => 'required',
            'category_type' => 'required',
        ]);

        $imagePath = $request->file('category_image')->store('faq_categories', 'public');

        // Prepend 'storage/' before saving:
        $publicPath = 'storage/' . $imagePath;

        product_category::create([
            'name' => $validated['category_name'],
            'image' => $publicPath,
            'status' => $validated['Categorystatus'],
            'category_type' => $validated['category_type'],
            'sub_categories' => json_encode([]), // <-- add this line
        ]);


        return response()->json(['success' => true, 'message' => 'Category created successfully']);
    }
    public function getFaq($faq_id)
    {
        $faq = Faq::with('category:id,name,image')->find($faq_id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'faq' => $faq
        ]);
    }

    public function deleteFaqs($faq_id)
    {
        $faq = Faq::find($faq_id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.'
            ], 404);
        }

        try {
            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred while deleting FAQ.'
            ], 500);
        }
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
                return $order->user_id;
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
            ->select('order_id', 'total', 'created_at', 'order_status')
            ->get();

        $queries = DB::table('queries')
            ->where('user_id', $user_id)
            ->get();

        $buyer->orders = $orders;
        $buyer->queries = $queries;

        // return $buyer;
        return view('admin.BuyerProfile', compact('buyer'));
    }


    public function settings()
    {
        $userId = session('user_details.user_id'); // get logged-in user ID from session

        // Get the current user's info for referral use
        $currentUser = User::find($userId);

        // Get users referred by this user
        $referredUsers = User::where('referred_by', $userId)
            ->get(['user_name', 'user_email', 'created_at']);
        $referredCount = $referredUsers->count();

        if (session('user_details.user_role') !== 'admin') {
            $seller = DB::table('seller')->where('user_id', $userId)->first();
            $personalInfo = json_decode($seller->personal_info, true);

            return view('pages.Settings', compact('seller', 'personalInfo', 'referredCount', 'referredUsers'));
        } else {
            $users = DB::table('users')->where('user_id', $userId)->first();

            // Pass the referral data even for admin (can be empty if irrelevant)
            return view('pages.Settings', compact('users', 'referredUsers', 'referredCount'));
        }
    }






    public function updatePersonalInfo(Request $request)
    {
        $userId = session('user_details.user_id');
        $userRole = session('user_details.user_role');

        // Validate the incoming request
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($userRole === 'admin') {
            // If admin, only update the user_name in users table
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['message' => 'User not found!'], 404);
            }

            $user->user_name = $validated['full_name']; // Only update full_name to user_name
            $user->save();

            return response()->json(['message' => 'Admin information updated successfully!']);
        }

        // Normal seller update
        $seller = Seller::where('user_id', $userId)->first();

        if (!$seller) {
            return response()->json(['message' => 'Seller not found!'], 404);
        }

        $personalInfo = $seller->personal_info ? json_decode($seller->personal_info, true) : [];

        $personalInfo['full_name'] = $validated['full_name'];
        $personalInfo['phone_no'] = $validated['phone_no'];
        $personalInfo['email'] = $validated['email'];
        $personalInfo['address'] = $validated['address'] ?? ($personalInfo['address'] ?? null);

        if ($request->hasFile('profile_picture')) {
            $imagePath = $request->file('profile_picture')->store('kyc_files', 'public');
            $personalInfo['profile_picture'] = 'storage/' . $imagePath;
        }

        $seller->personal_info = json_encode($personalInfo);
        $seller->save();

        return response()->json(['message' => 'Personal information updated successfully!']);
    }

    public function updateUserPassword(Request $request)
    {
        $userId = session('user_details.user_id');

        // Validate input
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Get the user
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!',
            ], 404);
        }

        // Check current password
        if (!Hash::check($request->old_password, $user->user_password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // Update password
        $user->user_password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully!',
        ]);
    }

    public function dashboardCounts(){
        try {
            $userId   = session('user_details.user_id');
            $userRole = session('user_details.user_role');

            // counts
            $orders = Order::where('status', 'pending')->count();

            $approved = Products::where('is_approved', 0)
                ->where('product_status', 0)
                ->count();

            $queries = Query::where('user_id', $userId)
                ->where('status', 'pending')
                ->count();

            $creditQuery = DB::table('credit_request');
            if ($userRole === 'freelancer') {
                $creditQuery->where('user_id', $userId);
            }
            $credit = $creditQuery->where('request_status', 'pending')->count();

            $seller = Seller::whereHas('user', fn($q) =>
                $q->where('user_role', 'seller')->where('status', 'pending')
            )->count();

            $freelancer = Seller::whereHas('user', fn($q) =>
                $q->where('user_role', 'freelancer')->where('status', 'pending')
            )->count();

            $sumSF = Seller::whereHas('user', fn($q) =>
                $q->whereIn('user_role', ['seller','freelancer'])->where('status','pending')
            )->count();

            return response()->json([
                'orders'     => $orders,
                'approved'   => $approved,
                'queries'    => $queries,
                'credit'     => $credit,
                'seller'     => $seller,
                'freelancer' => $freelancer,
                'sumSF'      => $sumSF,
            ]);

        } catch (\Exception $e) {
            \Log::error('Dashboard counts error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load counts'], 500);
        }
    }
}

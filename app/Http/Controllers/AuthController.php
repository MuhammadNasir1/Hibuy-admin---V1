<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Query;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;
use App\Mail\SellerRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{


    public function dashboard()
    {
        if (!session()->has('user_details')) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $userDetails = session('user_details');
        $userRole = $userDetails['user_role'];
        $userId = $userDetails['user_id'];

        $data = []; // This will hold role-based dashboard data
        $topStores = [];
        $topProducts = [];

        switch ($userRole) {
            case 'admin':
                // Revenue, counts
                $data['revenue'] = Order::where('order_status', '=', 'delivered')->sum('grand_total');
                $data['totalUsers'] = User::where('user_role', 'seller')->count();
                $data['totalBuyers'] = User::where('user_role', 'customer')->count();
                $data['totalOrders'] = Order::whereNotIn('order_status', ['shipped', 'delivered', 'cancelled', 'returned'])->count();
                $data['returnedOrders'] = Order::where('order_status', 'returned')->count();
                $data['totalProducts'] = $productCount = Products::Join('categories', 'products.product_category', '=', 'categories.id')
                    ->where('products.product_status', 1)
                    ->count();

                $data['totalReviews'] = Reviews::count();
                $data['totalQueries'] = Query::count();

                // Get all orders once
                $allOrders = Order::all();
                // 🟡 Total Pending Orders for All Sellers
                $data['totalPendingOrders'] = $allOrders->where('order_status', 'order_placed')->count();

                $data['pendingAmount'] = $allOrders->where('order_status', 'shipped')->sum(function ($order) {
                    $items = json_decode($order->order_items, true);
                    $total = 0;

                    foreach ($items as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }

                    // Add delivery fee (if it exists)
                    $total += $order->delivery_fee ?? 0;

                    return $total;
                });



                $topStores = Store::all()->map(function ($store) use ($allOrders) {
                    // Decode store name/logo from JSON
                    $storeProfile = json_decode($store->store_profile_detail, true);
                    $storeName = $storeProfile['store_name'] ?? 'Unnamed Store';
                    $storeLogo = $storeProfile['store_image'] ?? asset('asset/Ellipse 2.png');

                    // Get all product IDs of the store
                    $productIds = Products::where('store_id', $store->store_id)->pluck('product_id')->toArray();

                    $totalSold = 0;
                    $totalEarning = 0;

                    foreach ($allOrders as $order) {
                        $items = json_decode($order->order_items, true);
                        foreach ($items as $item) {
                            if (in_array($item['product_id'], $productIds)) {
                                $totalSold += $item['quantity'];
                                $totalEarning += $item['price'] * $item['quantity'];
                            }
                        }
                    }

                    return (object) [
                        'store_id' => $store->store_id,
                        'store_name' => $storeName,
                        'logo' => $storeLogo,
                        'items_sold' => $totalSold,
                        'total_earning' => $totalEarning,
                    ];
                })
                    ->filter(fn($store) => $store->items_sold > 0) // Optional: remove stores with no sales
                    ->sortByDesc('total_earning')
                    ->take(5)
                    ->values();


                // 🧮 Calculate total profit from all delivered orders
                $allProducts = Products::get()->keyBy('product_id');
                $data['totalProfit'] = 0;

                foreach ($allOrders as $order) {
                    if ($order->order_status !== 'delivered') {
                        continue;
                    }

                    $items = json_decode($order->order_items, true);

                    foreach ($items as $item) {
                        $productId = $item['product_id'];
                        $quantity = $item['quantity'];
                        $price = $item['price'];

                        $cost = isset($allProducts[$productId]) ? $allProducts[$productId]->purchase_price : 0;

                        $data['totalProfit'] += ($price - $cost) * $quantity;
                    }
                }

                $latestOrders = Order::select([
                    'order_id',
                    'user_id',
                    'tracking_id',
                    'order_items',
                    'total',
                    'delivery_fee',
                    'customer_name',
                    'phone',
                    'address',
                    'status',
                    'order_status',
                    'order_date',
                    'created_at'
                ])
                    ->orderBy('order_id', 'desc') // latest first
                    ->limit(5) // only get 5 rows
                    ->get()
                    ->map(function ($order) {
                        return (object) [
                            'date' => $order->created_at->format('M d'),
                            'customer_name' => $order->customer_name,
                            'status' => $order->status,
                            'total' => $order->total, // using total directly from DB
                            'phone' => $order->phone,
                        ];
                    });

                break;
            case 'seller':
                $sellerId = Seller::where('user_id', $userId)->value('seller_id');
                $storeId = Store::where('seller_id', $sellerId)->value('store_id');

                $productIds = $products = Products::where('store_id', $storeId)
                    ->where('product_status', 1)
                    ->pluck('product_id')
                    ->toArray();
                // Product count
                $data['totalProducts'] = count($productIds);

                // Orders
                $orders = Order::get()->filter(function ($order) use ($productIds) {
                    $items = json_decode($order->order_items, true);
                    foreach ($items as $item) {
                        if (in_array($item['product_id'], $productIds)) {
                            return true;
                        }
                    }
                    return false;
                });

                $data['totalOrders'] = $orders->whereNotIn('order_status', ['shipped', 'delivered', 'cancelled', 'returned'])->count();
                $data['returnedOrders'] = $orders->where('order_status', 'returned')->count();
                $data['totalPendingOrders'] = $orders->where('order_status', 'order_placed')->count();

                // ➕ Calculate Pending Amount
                $pendingOrders = $orders->where('order_status', 'order_placed');
                $data['pendingAmount'] = $pendingOrders->sum(function ($order) use ($productIds) {
                    $items = json_decode($order->order_items, true);
                    $total = 0;
                    foreach ($items as $item) {
                        if (in_array($item['product_id'], $productIds)) {
                            $total += $item['price'] * $item['quantity'];
                        }
                    }
                    return $total;
                });

                // Load all seller products once to avoid repeated DB queries
                $products = Products::whereIn('product_id', $productIds)->get()->keyBy('product_id');

                $data['revenue'] = 0;
                $data['totalProfit'] = 0;

                $data['revenue'] = $orders->sum(function ($order) use ($productIds, $products, &$data) {
                    // ✅ Only process delivered orders
                    if ($order->order_status !== 'delivered') {
                        return 0;
                    }

                    $items = json_decode($order->order_items, true);
                    $total = 0;
                    $profit = 0;

                    foreach ($items as $item) {
                        if (in_array($item['product_id'], $productIds)) {
                            $quantity = $item['quantity'];
                            $price = $item['price']; // unit selling price
                            $cost = isset($products[$item['product_id']]) ? $products[$item['product_id']]->purchase_price : 0;

                            $total += $price * $quantity;
                            $profit += ($price - $cost) * $quantity;
                        }
                    }

                    $data['totalProfit'] += $profit;

                    return $total;
                });


                //  Calculate Total Expense (purchase_price * stock)
                $data['totalExpense'] = $products->sum(function ($product) {
                    return $product->purchase_price * $product->product_stock;
                });
                $data['totalReviews'] = Reviews::whereIn('product_id', $productIds)->count();

                //  Top Selling Products
                $topProducts = Products::whereIn('product_id', $productIds)
                    ->get()
                    ->map(function ($product) use ($orders) {
                        $sold = 0;
                        $earning = 0;

                        // Decode the JSON image array
                        $images = json_decode($product->product_images, true);
                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;

                        foreach ($orders as $order) {
                            $items = json_decode($order->order_items, true);
                            foreach ($items as $item) {
                                if ($item['product_id'] == $product->product_id) {
                                    $sold += $item['quantity'];
                                    $earning += $item['price'];
                                }
                            }
                        }

                        return (object) [
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'image' => $firstImage,
                            'total_sold' => $sold,
                            'total_earning' => $earning,
                        ];
                    })
                    ->sortByDesc('total_sold')
                    ->take(5)
                    ->values();


                $storeId = Store::where('seller_id', $sellerId)->value('store_id');
                $productIds = Products::where('store_id', $storeId)->pluck('product_id')->toArray();

                $latestOrders = Order::select([
                    'order_id',
                    'user_id',
                    'tracking_id',
                    'order_items',
                    'total',
                    'delivery_fee',
                    'customer_name',
                    'phone',
                    'address',
                    'status',
                    'order_status',
                    'order_date',
                    'created_at'
                ])
                    ->orderBy('order_id', 'desc')
                    ->get()
                    ->map(function ($order) use ($productIds) {
                        $orderItems = json_decode($order->order_items, true) ?? [];


                        $filteredOrderItems = array_filter($orderItems, function ($item) use ($productIds) {
                            return in_array($item['product_id'], $productIds);
                        });


                        if (empty($filteredOrderItems)) {
                            return null;
                        }


                        $grandTotal = array_sum(array_column($filteredOrderItems, 'price'));


                        return (object) [
                            'date' => $order->created_at->format('M d'),
                            'customer_name' => $order->customer_name,
                            'status' => $order->status,
                            'total' => $order->total,
                            'phone' => $order->phone,
                        ];
                    })
                    ->filter()
                    ->take(5)
                    ->values();

                break;


            case 'freelancer':
                // Get freelancer-specific info
                break;

            default:
                return redirect()->route('login')->with('error', 'Invalid user role.');
        }

        return view('pages.dashboard', compact('data', 'userRole', 'topStores', 'topProducts', 'latestOrders'));
    }


    public function showSignup($role = null)
    {
        $allowedRoles = ['freelancer', 'seller'];

        if (!$role || !in_array($role, $allowedRoles)) {
            return redirect()->route('login');
        }
        return view('Auth.signup', ['role' => $role]);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_name' => 'required|string',
                'user_email' => 'required|string|email|unique:users',
                'user_password' => 'required|min:8',
                'user_role' => 'required|string',
            ]);

            $referralCode = $request->input('referred_by') ?? $request->query('ref');
            $referredBy = null;

            if ($referralCode) {
                $decodedArray = Hashids::decode($referralCode);
                $decodedId = $decodedArray[0] ?? null;

                if ($decodedId && User::where('user_id', $decodedId)->exists()) {
                    $referredBy = $decodedId;
                }
            }

            $user = User::create([
                'user_name' => $validatedData['user_name'],
                'user_email' => $validatedData['user_email'],
                'user_password' => Hash::make($validatedData['user_password']),
                'user_role' => $validatedData['user_role'],
                'referred_by' => $referredBy,
            ]);

            if ($validatedData['user_role'] === 'customer') {
                Customer::create(['user_id' => $user->user_id]);
            } elseif (in_array($validatedData['user_role'], ['seller', 'freelancer'])) {
                Seller::create(['user_id' => $user->user_id]);
            }

            return response()->json(['success' => true, 'message' => "Register Successfully"], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'user_email' => 'required|string|email|max:255',
            'user_password' => 'required|min:6'
        ]);
        // Find user by email
        $user = User::where('user_email', $validatedData['user_email'])
            ->whereIn('user_role', ['seller', 'freelancer', 'admin'])
            ->first();

        // If user not found
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'errors' => ['user_email' => ['User not found.']]
            ], 404);
        }

        // If user found and password is incorrect
        if (!Hash::check($validatedData['user_password'], $user->user_password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.',
                'errors' => ['user_password' => ['Incorrect password.']]
            ], 401);
        }

        if ($user->user_role === 'seller' && $user->user_status == 0) {
            return response()->json([
                'success' => false,
                'status' => 'disabled',
                'message' => 'Your account is disabled. Please contact the administrator.',
                'disabled_reason' => $user->disabled_reason ?? 'Not specified'
            ], 403);
        }

        // Get seller KYC status
        $kyc_status = Seller::where('user_id', $user->user_id)->first();
        $store = Store::where('user_id', $user->user_id)->first();
        $store_id = $store ? $store->store_id : null;
        $store_name = null;
        // Extract store_name from JSON column if available
        if ($store && !empty($store->store_profile_detail)) {
            $profileDetail = json_decode($store->store_profile_detail, true);
            $store_name = $profileDetail['store_name'] ?? null;
        }
        // Regenerate session to prevent session fixation attacks
        session()->regenerate();

        // Store user_id and user_role in session
        session([
            'user_details' => [
                'user_id' => $user->user_id,
                'user_role' => $user->user_role,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'store_id' => $store_id,
                'store_name' => $store_name,
            ]
        ]);
        $role = $user->user_role;
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'seller_status' => @$kyc_status->status,
            'user' => [
                'id' => $user->user_id,
                'name' => $user->user_name,
                'email' => $user->user_email,
                'user_role' => $user->user_role
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        // Check if the user is authenticated
        if (!session()->has('user_details')) {
            return response()->json([
                'success' => false,
                'message' => 'User not logged in.',
            ], 401);
        }

        // Destroy the session
        session()->forget('user_details');
        session()->invalidate();
        session()->regenerateToken(); // Regenerate token for security

        return redirect('../');
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Logout successful.',
        // ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validatedData = $request->validate([
            'user_email' => 'required|email|exists:users,user_email',
        ]);

        // Check if the user exists with allowed roles
        $user = User::where('user_email', $validatedData['user_email'])
            ->whereIn('user_role', ['seller', 'freelancer'])
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Your account doesn’t have access to this feature.'
            ], 403);
        }

        // Generate reset token
        $token = Str::random(65);
        DB::table('password_forgot')->where('email', $validatedData['user_email'])->delete();

        // Store token in the password_forgot table
        DB::table('password_forgot')->insert([
            'email' => $validatedData['user_email'],
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            // Send reset email with both token and email
            // Mail::to($validatedData['user_email'])->send(new ForgotPasswordMail($token, $validatedData['user_email'],$user->user_name));
            Mail::to($validatedData['user_email'])
                ->queue(new ForgotPasswordMail($token, $validatedData['user_email'], $user->user_name));
            return response()->json([
                'message' => 'We have sent a reset password link to your email.'
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to send password reset email: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred. Please try again later.',
            ], 500);
        }
    }



    public function showLinkForm($token, Request $request)
    {
        // You can validate the token here if necessary
        $email = $request->query('email'); // Get the email from the URL
        return view('Auth.forgotPasswordForm', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            "token" => 'required',
            "email" => 'required|email',
            "user_password" => 'required|confirmed|min:8',
        ]);

        // Find token entry
        $record = DB::table("password_forgot")
            ->where("email", $request->email)
            ->where("token", $request->token)
            ->first();

        // If token not found
        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired Link.',
            ], 400);
        }

        // Check if token is expired (older than 5 minutes)
        $tokenCreatedAt = \Carbon\Carbon::parse($record->created_at);
        if ($tokenCreatedAt->diffInMinutes(now()) > 5) {

            DB::table("password_forgot")
                ->where("email", $request->email)
                ->where("token", $request->token)
                ->delete();

            return response()->json([
                'success' => false,
                'message' => 'This password reset link has expired',
            ], 410);
        }

        // Check if user exists
        $user = User::where("user_email", $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user found with this email address.',
            ], 404);
        }

        // Update password
        $user->user_password = Hash::make($request->user_password);
        $user->save();

        // Delete token
        DB::table("password_forgot")
            ->where("email", $request->email)
            ->where("token", $request->token)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
            'redirect' => route('login')
        ]);
    }
}

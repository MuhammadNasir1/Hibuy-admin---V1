<?php

namespace App\Http\Controllers\Api;

use Google_Client;
use App\Models\User;
use App\Models\Order;
use App\Models\Query;
use App\Models\Reviews;
use App\Models\Customer;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class apiAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'user_email' => 'required|string|email|max:255',
                'user_password' => 'required|min:6'
            ]);

            // Fetch user where role is 'customer'
            $user = User::where('user_email', $validatedData['user_email'])
                ->where('user_role', 'customer') // Allow only customers
                ->with(['customer']) // Load customer relationship
                ->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($validatedData['user_password'], $user->user_password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password.',
                    'errors' => ['user_email' => ['Invalid email or password.']]
                ], 401);
            }

            // Generate API token
            $token = $user->createToken('api-token')->plainTextToken;

            // Prepare user data
            $userData = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'user_role' => $user->user_role
            ];

            // Merge customer-specific details
            if ($user->customer) {
                $customerData = $user->customer->toArray();

                // Decode the `customer_addresses` JSON string into an array
                $customerData['customer_addresses'] = json_decode($customerData['customer_addresses'], true);

                // Merge with user data
                $userData = array_merge($userData, $customerData);
            }

            return response()->json([
                'success' => true,
                'message' => "Login successful",
                'access_token' => $token,
                'user' => $userData
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }




    public function logout(Request $request)
    {
        try {
            // Revoke the current user's token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function userdetail()
    {
        try {
            // Check if the user is authenticated
            $loggedInUser = Auth::user();

            // Fetch user with relationships
            $user = User::where('user_id', $loggedInUser->user_id)
                ->with(['customer', 'seller', 'stores']) // Load relationships
                ->first();

            // If user is not found, return a 404 response
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            // Initialize user data
            $userData = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'user_role' => $user->user_role
            ];

            // Merge additional details based on role
            if ($user->user_role === 'customer' && $user->customer) {
                $userData = array_merge($userData, $user->customer->toArray());
            } elseif (in_array($user->user_role, ['seller', 'freelancer']) && $user->seller) {
                $sellerData = $user->seller->toArray();

                // Decode JSON fields from seller table if they are not null
                $jsonFields = ['personal_info', 'store_info', 'documents_info', 'bank_info'];
                foreach ($jsonFields as $field) {
                    if (!empty($sellerData[$field])) {
                        $sellerData[$field] = json_decode($sellerData[$field], true);
                    }
                }

                // Check if a store exists for this seller
                $store = $user->stores->where('seller_id', $user->seller->seller_id)->first();

                if ($store) {
                    // If store exists, use store_profile_detail from stores table
                    if (!empty($store->store_profile_detail)) {
                        $sellerData['store_info'] = json_decode($store->store_profile_detail, true);
                    }
                }

                // Merge seller details
                $userData = array_merge($userData, $sellerData);
            }

            return response()->json([
                'success' => true,
                'message' => "User fetch successful",
                'user' => $userData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeReview(Request $request)
    {
        try {
            $User = Auth::user();

            // Validate input
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|exists:products,product_id',
                'order_id'   => 'required|integer',
                'rating'     => 'required|integer|min:1|max:5',
                'review'     => 'required|string',
                'images'     => 'nullable|array',
                'images.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // Check if the order belongs to the authenticated user
            $order = Order::where('order_id', $request->order_id)
                ->where('user_id', $User->user_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order ID for this user',
                ], 403);
            }

            // Prevent duplicate reviews
            $existingReview = Reviews::where('user_id', $User->user_id)
                ->where('product_id', $request->product_id)
                ->where('order_id', $request->order_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already submitted a review for this product in this order.',
                ], 409);
            }

            // Sanitize review text
            $cleanedReview = strip_tags($request->review);

            // Handle image upload
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('review_images', 'public');
                    $imagePaths[] = Storage::url($path); // e.g., /storage/review_images/file.jpg
                }
            }

            $orderId = $request->order_id;

            if (!$orderId) {
                // Use latest order as fallback
                $latestOrder = Order::where('user_id', $User->user_id)
                    ->latest()
                    ->first();

                if (!$latestOrder) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No order found for this user',
                    ], 404);
                }

                $orderId = $latestOrder->order_id;
            }
            // dd($request->order_id);
            // Create the review
            $review = Reviews::create([
                'user_id'    => $User->user_id,
                'product_id' => $request->product_id,
                'order_id'   => $orderId, // ✅ uses provided or fallback
                'rating'     => $request->rating,
                'review'     => $cleanedReview,
                'images'     => json_encode($imagePaths),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review added successfully',
                'data'    => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getReviews()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => "User Not Found"
                ], 404);
            }

            // Eager load product and order
            $reviews = Reviews::where('user_id', $user->user_id)
                ->with(['product', 'order'])
                ->get();

            $reviews->each(function ($review) use ($user) {
                // Parse review images (keep full array)
                $reviewImages = json_decode($review->images, true);
                $review->images = is_array($reviewImages) ? $reviewImages : [];

                // Attach username
                $review->user_name = $user->user_name;

                // Format product info: only first image
                if ($review->product) {
                    $productImagesRaw = $review->product->product_images;

                    $productImages = is_string($productImagesRaw)
                        ? json_decode($productImagesRaw, true)
                        : (is_array($productImagesRaw) ? $productImagesRaw : []);

                    $firstImage = is_array($productImages) ? ($productImages[0] ?? null) : null;

                    // Attach clean product_detail
                    $review->product_detail = [
                        'product_id'    => $review->product->product_id,
                        'product_title' => $review->product->product_name,
                        'product_image' => $firstImage,
                    ];

                    // ✅ Properly remove the original relation
                    $review->unsetRelation('product');
                }

                // Format order info: only order_date + extract variation
                if ($review->order) {
                    // Keep original order before replacing it
                    $originalOrder = $review->order;

                    // dd($originalOrder);
                    // Replace with just specific field(s)
                    // Start with order date
                    $orderData = [
                        'order_id' => $originalOrder->order_id,
                        'order_date' => $originalOrder->order_date,
                    ];

                    // Decode order_items and extract selected variation
                    $orderItems = json_decode($originalOrder->order_items, true);
                    if (is_array($orderItems)) {
                        foreach ($orderItems as $item) {
                            if ((int)$item['product_id'] === (int)$review->product_id) {
                                $orderData['parent_option_name']  = $item['parent_option']['name'] ?? null;
                                $orderData['parent_option_value'] = $item['parent_option']['value'] ?? null;
                                $orderData['child_option_name']   = $item['child_option']['name'] ?? null;
                                $orderData['child_option_value']  = $item['child_option']['value'] ?? null;
                                break;
                            }
                        }
                    }

                    // Attach only the clean, formatted order data
                    $review->orderDetails = $orderData;
                    // ✅ Properly remove the original relation
                    $review->unsetRelation('order');
                }
            });
            // dd($reviews);

            return response()->json([
                'success' => true,
                'message' => "Reviews fetched successfully",
                'reviews' => $reviews,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }





    public function editProfile(Request $request)
    {
        try {
            $user = Auth::user(); // Fetch user from token
            if (!$user) {
                return response()->json(['success' => false, 'message' => "User Not Found"], 404);
            }

            $customer = Customer::where('user_id', $user->user_id)->first();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => "Customer Not Found"], 404);
            }

            // Check if an image is uploaded

            if ($request->hasFile('customer_image')) {
                $image = $request->file('customer_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('customers', $imageName, 'public'); // Store in storage/app/public/customers

                // Delete old image if exists
                if ($customer->customer_image) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $customer->customer_image));
                }

                // Store the required path format
                $customer->customer_image = 'storage/' . $path; // e.g., storage/customers/123456.jpg
            }

            // Update user details
            $user->user_name = $request->user_name;
            $user->save();

            // Update customer details
            $customer->customer_phone = $request->customer_phone;
            $customer->customer_gender = $request->customer_gender;
            $customer->customer_dob = $request->customer_dob;
            $customer->update();

            // Update password if provided
            if ($request->filled('old_password') && $request->filled('new_password') && $request->filled('confirm_password')) {
                if (!Hash::check($request->old_password, $user->user_password)) {
                    return response()->json(['success' => false, 'message' => "Old password is incorrect"], 400);
                }

                if ($request->new_password !== $request->confirm_password) {
                    return response()->json(['success' => false, 'message' => "New password and confirm password do not match"], 400);
                }

                // Update password
                $user->user_password = Hash::make($request->new_password);
                $user->save();
            }

            // Prepare user data
            $userData = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'user_role' => $user->user_role,
            ];

            // Merge customer-specific details
            if ($customer) {
                $customerData = $customer->toArray();

                // Decode the `customer_addresses` JSON string into an array
                if (!empty($customerData['customer_addresses'])) {
                    $customerData['customer_addresses'] = json_decode($customerData['customer_addresses'], true);
                }

                // Merge with user data
                $userData = array_merge($userData, $customerData);
            }

            return response()->json([
                'success' => true,
                'message' => "Profile Updated Successfully",
                'user' => $userData
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function storeAddress(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => "User Not Found"], 404);
            }

            $customer = Customer::where('user_id', $user->user_id)->first();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => "Customer Not Found"], 404);
            }

            $addresses = json_decode($customer->customer_addresses, true) ?? [];
            $isUpdate = false;

            if ($request->has('address_id') && !empty($request->address_id)) {
                // Update existing address
                foreach ($addresses as &$addr) {
                    if ($addr['address_id'] === $request->address_id) {
                        $addr['first_name'] = $request->first_name;
                        $addr['last_name'] = $request->last_name;
                        $addr['phone_number'] = $request->phone_number;
                        $addr['second_phone_number'] = $request->second_phone_number;
                        $addr['address_line'] = $request->address_line;
                        $addr['is_default'] = $request->is_default ?? false;
                        $isUpdate = true;
                        break;
                    }
                }
            }

            if (!$isUpdate) {
                // Add new address
                $newAddress = [
                    'address_id' => uniqid(),
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_number' => $request->phone_number,
                    'second_phone_number' => $request->second_phone_number,
                    'address_line' => $request->address_line,
                    'is_default' => $request->is_default ?? false,
                ];

                $addresses[] = $newAddress;
            }

            // If the current address is set as default, remove default from others
            if ($request->is_default) {
                foreach ($addresses as &$addr) {
                    if ($addr['address_id'] !== ($request->address_id ?? $newAddress['address_id'])) {
                        $addr['is_default'] = false;
                    }
                }
            }

            // Sort addresses to ensure the default one comes first
            usort($addresses, function ($a, $b) {
                return $b['is_default'] <=> $a['is_default'];
            });

            $customer->customer_addresses = json_encode($addresses);
            $customer->save();

            // Prepare user data
            $userData = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'user_role' => $user->user_role,
            ];

            // Merge customer-specific details
            $customerData = $customer->toArray();
            if (!empty($customerData['customer_addresses'])) {
                $customerData['customer_addresses'] = json_decode($customerData['customer_addresses'], true);
            }

            $userData = array_merge($userData, $customerData);

            return response()->json([
                'success' => true,
                'message' => $isUpdate ? "Address updated successfully" : "Address added successfully",
                'user' => $userData, // Returns user and customer details with default address first
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }


    public function getAddress()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => "User Not Found"], 404);
            }

            $customer = Customer::where('user_id', $user->user_id)->first();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => "Customer Not Found"], 404);
            }

            // Decode addresses
            $addresses = json_decode($customer->customer_addresses, true) ?? [];

            // Sort so default address appears first
            usort($addresses, function ($a, $b) {
                return $b['is_default'] <=> $a['is_default'];
            });

            // Prepare user data
            $userData = [
                'user_id'    => $user->user_id,
                'user_name'  => $user->user_name,
                'user_email' => $user->user_email,
                'user_role'  => $user->user_role,
            ];

            // Merge customer-specific details
            $customerData = $customer->toArray();
            $customerData['customer_addresses'] = $addresses;

            $userData = array_merge($userData, $customerData);

            return response()->json([
                'success' => true,
                'message' => "Customer addresses fetched successfully",
                'user'    => $userData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function deleteAddress(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => "User Not Found"], 404);
            }

            $customer = Customer::where('user_id', $user->user_id)->first();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => "Customer Not Found"], 404);
            }

            $addresses = json_decode($customer->customer_addresses, true) ?? [];
            $deletedAddress = null;

            // Identify if the deleted address was the default one
            foreach ($addresses as $addr) {
                if ($addr['address_id'] === $request->address_id) {
                    $deletedAddress = $addr;
                    break;
                }
            }

            // Remove the address
            $filteredAddresses = array_values(array_filter($addresses, fn($addr) => $addr['address_id'] !== $request->address_id));

            if (count($addresses) === count($filteredAddresses)) {
                return response()->json(['success' => false, 'message' => "Address not found"], 404);
            }

            // If the deleted address was the default one, make another address default
            if ($deletedAddress && $deletedAddress['is_default'] && !empty($filteredAddresses)) {
                $filteredAddresses[0]['is_default'] = true;
            }

            // Sort addresses to ensure the default one comes first
            usort($filteredAddresses, fn($a, $b) => $b['is_default'] <=> $a['is_default']);

            $customer->customer_addresses = json_encode($filteredAddresses);
            $customer->save();

            // Prepare user data
            $userData = [
                'user_id' => $user->user_id,
                'user_name' => $user->user_name,
                'user_email' => $user->user_email,
                'user_role' => $user->user_role,
            ];

            // Merge customer-specific details
            $customerData = $customer->toArray();

            // Decode `customer_addresses` JSON string into an array
            if (!empty($customerData['customer_addresses'])) {
                $customerData['customer_addresses'] = json_decode($customerData['customer_addresses'], true);
            }

            // Merge user and customer data
            $userData = array_merge($userData, $customerData);

            return response()->json([
                'success' => true,
                'message' => "Address deleted successfully",
                'user' => $userData, // Returns user and customer details with updated addresses
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }



    public function addQuery(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'subject' => 'required',
                'message' => 'required',
                'status' => 'required',
            ]);

            $query = new Query();

            $query->user_id = $user->user_id;
            $query->email = $user->user_email;
            $query->subject = $request->subject;
            $query->message = $request->message;
            $query->status = $request->status;

            $query->save();

            return response()->json(['sucess' => true, 'message' => 'Query Added Successfully']);
        } catch (\Exception $e) {
            return response()->json(['sucess' => false, 'message' => $e->getMessage()]);
        }
    }

    // For google login

    // Redirect to Google for authentication
    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // Handle Google callback and verify token
    public function handleGoogleCallback(Request $request)
    {
        $tokenId = $request->input('tokenId');

        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);

        try {
            $payload = $client->verifyIdToken($tokenId);

            if ($payload) {
                $googleId = $payload['sub'];
                $email = $payload['email'];
                $name = $payload['name'];

                // Find user by google_id or user_email
                $user = User::where('google_id', $googleId)
                    ->orWhere('user_email', $email)
                    ->first();

                $isNewUser = false;

                if (!$user) {
                    $user = User::create([
                        'google_id' => $googleId,
                        'user_name' => $name,
                        'user_email' => $email,
                        'user_role' => 'customer',
                        'user_password' => Hash::make(uniqid()),
                    ]);

                    $isNewUser = true;
                } elseif (!$user->google_id) {
                    $user->google_id = $googleId;
                    $user->save();
                }

                // Create customer record if new user
                if ($isNewUser) {
                    \App\Models\Customer::create([
                        'user_id' => $user->user_id
                    ]);
                }

                // Create Sanctum token
                $token = $user->createToken('google-login-token')->plainTextToken;

                // Prepare user data
                $userData = $user->toArray();

                // Merge customer-specific details
                if ($user->customer) {
                    $customerData = $user->customer->toArray();

                    // Decode customer_addresses JSON if present
                    if (!empty($customerData['customer_addresses'])) {
                        $customerData['customer_addresses'] = json_decode($customerData['customer_addresses'], true);
                    }

                    // Merge into user data
                    $userData = array_merge($userData, $customerData);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'access_token' => $token,
                    'user' => $userData,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token verification failed',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}

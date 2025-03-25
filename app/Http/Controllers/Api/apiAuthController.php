<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Reviews;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
                'rating'     => 'required|integer|min:1|max:5',
                'review'     => 'required|string',
                'images'     => 'nullable|array',
                'images.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Optional image validation
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], 422);
            }

            // Handle image upload
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $image->store('review_images', 'public'); // Store in `storage/app/public/review_images`
                }
            }

            // Create the review
            $review = Reviews::create([
                'user_id'    => $User->id, // Get user_id from authenticated user
                'product_id' => $request->product_id,
                'rating'     => $request->rating,
                'review'     => $request->review,
                'images'     => json_encode($imagePaths), // Store image paths as JSON
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review added successfully',
                'review'  => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error'   => $e->getMessage()
            ], 500);
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
                        $addr['city'] = $request->city;
                        $addr['province'] = $request->province;
                        $addr['area'] = $request->area;
                        $addr['postal_code'] = $request->postal_code;
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
                    'city' => $request->city,
                    'province' => $request->province,
                    'area' => $request->area,
                    'postal_code' => $request->postal_code,
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
                'user' => $userData, // Returns user and customer details
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
            $filteredAddresses = array_filter($addresses, fn($addr) => $addr['address_id'] !== $request->address_id);

            if (count($addresses) === count($filteredAddresses)) {
                return response()->json(['success' => false, 'message' => "Address not found"], 404);
            }

            $customer->customer_addresses = json_encode(array_values($filteredAddresses));
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
                'user' => $userData, // Returns user and customer details
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
}

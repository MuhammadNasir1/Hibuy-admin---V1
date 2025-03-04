<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

            // Fetch user with relationships
            $user = User::where('user_email', $validatedData['user_email'])
                ->with(['customer', 'seller']) // Load relationships
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
                $userData = array_merge($userData, $user->seller->toArray());
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
}

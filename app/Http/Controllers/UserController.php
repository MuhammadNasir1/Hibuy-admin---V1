<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function editProfile(Request $request)
    {
        try {
            $user  = Auth::user();
            $customer  = Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => "Customer Not Found"], 404);
            }
            if ($request->hasFile('customer_image')) {
                $image = $request->file('customer_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $customer->customer_image = $imageName;
            }
            $user->user_name = $request->user_name;
            $user->user_email = $request->user_email;
            $user->save();

            $customer->customer_phone = $request->customer_phone;
            $customer->customer_gender = $request->customer_gender;
            $customer->customer_dob = $request->customer_dob;
            $customer->update();


            return response()->json(['success' => true, 'message' => "Profile Updated Successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
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
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = Auth::user();

            // Find the seller record for the authenticated user
            $seller = Seller::where('user_id', $user->user_id)->first();
            if (!$seller) {
                return response()->json(['error' => 'Seller record not found'], 404);
            }

            // Define mapping of step numbers to database columns
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
                'data' => 'required|array', // Ensures the data is an array
                'seller_type' => 'required', // Ensures the data is an array
            ]);

            $step = $validatedData['step'];
            $data = $validatedData['data'];
            $seller_type = $validatedData['seller_type'];

            // Verify the step exists in the mapping
            if (!isset($stepMapping[$step])) {
                return response()->json(['error' => 'Invalid step'], 400);
            }

            // Get the column name based on step
            $column = $stepMapping[$step];

            // Encode data to JSON safely
            $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON data'], 400);
            }

            // Store the JSON data in the corresponding column
            $seller->$column = $jsonData;
            $seller->seller_type = $seller_type;
            $seller->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst(str_replace('_', ' ', $column)) . ' updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}

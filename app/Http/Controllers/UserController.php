<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Customer;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{

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
            $textData = $request->except(['_token', 'step', 'profile_picture', 'front_image', 'back_image']);
            $fileData = [];

            // Define custom upload path
            $uploadPath = public_path('uploads/kyc_files');

            // Ensure the directory exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Handle file uploads
            foreach (['profile_picture', 'front_image', 'back_image'] as $fileField) {
                if ($request->hasFile($fileField)) {
                    $file = $request->file($fileField);
                    $fileName = time() . '_' . $fileField . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fileName);
                    $fileData[$fileField] = 'uploads/kyc_files/' . $fileName; // Store relative path
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
}

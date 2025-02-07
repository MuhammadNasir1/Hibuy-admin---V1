<?php

namespace App\Http\Controllers;

use App\Mail\SellerRegistration;
use App\Models\Customer;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_name' => 'required|string',
                'user_email' => 'required|string|email|unique:users',
                'user_password' => 'required|min:8',
                'user_role' => 'required|string',
            ]);
            $user = User::create([
                'user_name' => $validatedData['user_name'],
                'user_email' => $validatedData['user_email'],
                'user_password' => Hash::make($validatedData['user_password']),
                'user_role' => $validatedData['user_role'],
            ]);

            if($validatedData['user_role'] == 'customer'){
                Customer::create([
                    'user_id' => $user->user_id,
                ]);
            }else if($validatedData['user_role'] == 'seller'){
                Seller::create([
                    'user_id' => $user->user_id,
                ]);
                // Mail::to($validatedData['user_email'])->send(new SellerRegistration($user->user_name, asset("setupPassword?i=" .  Crypt::encrypt($user->user_id))));
            }
            return response()->json(['success' => true, 'message' => "Register Successfully"], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_email' => 'required|string|email',
                'user_password' => 'required'
            ]);
            $user = User::where('user_email', $validatedData['user_email'])->first();
            if (!$user || !Hash::check($validatedData['user_password'], $user->user_password)) {
                return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            }
        $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['success' => true, 'message' => "Login successfully", 'access_token' => $token, 'user' => $user ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}

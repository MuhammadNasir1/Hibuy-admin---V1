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
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function showSignup($role = null)
    {
        $allowedRoles = ['freelancer', 'seller'];

        if (!$role || !in_array($role, $allowedRoles)) {
            return redirect()->route('login');
        }
        return view('auth.signup', ['role' => $role]);
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
            $user = User::create([
                'user_name' => $validatedData['user_name'],
                'user_email' => $validatedData['user_email'],
                'user_password' => Hash::make($validatedData['user_password']),
                'user_role' => $validatedData['user_role'],
            ]);

            if ($validatedData['user_role'] == 'customer') {
                Customer::create([
                    'user_id' => $user->user_id,
                ]);
            } else if ($validatedData['user_role'] == 'seller' || $validatedData['user_role'] == 'freelancer') {
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
        // Validate request
        $validatedData = $request->validate([
            'user_email' => 'required|string|email|max:255',
            'user_password' => 'required|min:6'
        ]);

        // Find user by email
        $user = User::where('user_email', $validatedData['user_email'])
            ->whereIn('user_role', ['seller', 'freelancer', 'admin'])
            ->first();
            $kyc_status = Seller::Where('user_id' , $user->user_id)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validatedData['user_password'], $user->user_password)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
                'errors' => ['user_email' => ['Invalid email or password.']]
            ], 401);
        }

        // Regenerate session to prevent session fixation attacks
        session()->regenerate();

        // Store user_id and user_role in session
        session([
            'user_details' => [
                'user_id' => $user->user_id,
                'user_role' => $user->user_role
            ]
        ]);
        $role  = $user->user_role;
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
}

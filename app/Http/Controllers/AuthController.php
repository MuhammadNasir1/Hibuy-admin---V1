<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Customer;
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

        // Get seller KYC status
        $kyc_status = Seller::where('user_id', $user->user_id)->first();
        $store = Store::where('user_id', $user->user_id)->first();
        $store_id = $store ? $store->store_id : null;
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

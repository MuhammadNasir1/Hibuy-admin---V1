<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DisableReasonController extends Controller
{

    public function disableSeller(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:users,user_id',
            'user_status' => 'required|in:0,1',
            'disabled_reason' => 'nullable|string|max:255'
        ]);

        $user = User::where('user_id', $validated['seller_id'])->first();

        if (!$user || $user->user_role !== 'seller') {
            return response()->json([
                'success' => false,
                'message' => 'User not found or is not a seller.'
            ], 404);
        }

        $user->user_status = $validated['user_status'];

        if ($validated['user_status'] == 0) {
            $user->disabled_reason = $validated['disabled_reason'] ?? 'Disabled manually';
        } else {
            $user->disabled_reason = null;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Seller status updated successfully.'
        ]);
    }

    public function getSellerStatus(Request $request,$sellerId): JsonResponse
    {

        $user = User::where('user_id', $sellerId)
            ->where('user_role', 'seller')
            ->first(['user_status', 'disabled_reason']);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
}

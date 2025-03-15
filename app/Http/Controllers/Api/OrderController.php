<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            // Ensure the user is authenticated
            $loggedInUser = Auth::user();
            // return $request->items;
            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // Validate request data
            $validatedData = $request->validate([
                'items'        => 'required|array',
                'total'        => 'required|numeric',
                'delivery_fee' => 'nullable|numeric',
                'grand_total'  => 'required|numeric',
                'address'      => 'required|array',
            ]);
            // return $validatedData['items'];
            // Generate a tracking ID
            $trackingId = 'TRK-' . strtoupper(uniqid());
            $items = is_array($validatedData['items']) ? $validatedData['items'] : json_decode($validatedData['items'], true);
            // return $items;

            // Create the order
            $order = Order::create([
                'user_id'       => $loggedInUser->user_id,
                'tracking_id'   => $trackingId,
                'order_items'   => json_encode($items), // Convert items to JSON
                'total'         => $validatedData['total'],
                'delivery_fee'  => $validatedData['delivery_fee'] ?? 0,
                'grand_total'   => $validatedData['grand_total'],
                'customer_name' => $validatedData['address']['name'],
                'phone'         => $validatedData['address']['phone'],
                'address'       => $validatedData['address']['address'],
                'second_phone'  => $validatedData['address']['second_phone'] ?? null,
                'order_date'    => now()->format('M d, Y'), // Example: "Mar 15, 2025"
                'status'        => 'pending',
            ]);

            return response()->json(['success' => true, 'message' => 'Order placed successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

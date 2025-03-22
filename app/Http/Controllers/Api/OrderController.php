<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Products;

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
                'order_status'  => 'order_placed',
            ]);

            // Decode order_items
            $orderItems = json_decode($order->order_items, true);

            // Extract product IDs from order_items
            $productIds = array_column($orderItems, 'product_id');

            $products = Products::select(
                'product_id',
                'product_name',
                'product_brand',
                'product_price',
                'product_discounted_price'
            )
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            // Attach product details to each order item
            foreach ($orderItems as &$item) {
                $item['product_details'] = $products[$item['product_id']] ?? null;
            }

            // Update order items with detailed product info
            $order->order_items = $orderItems;

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order'   => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function GetOrders()
    {
        try {
            // Ensure the user is authenticated
            $loggedInUser = Auth::user();

            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // Fetch selected columns from orders
            $orders = Order::select(
                'order_id',
                'tracking_id',
                'grand_total',
                'customer_name',
                'status',
                'order_status',
                'order_date'
            )
                ->where('user_id', $loggedInUser->user_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Orders fetched successfully',
                'orders'  => $orders
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function GetOrderDetail(Request $request)
    {
        try {
            // Ensure the user is authenticated
            $loggedInUser = Auth::user();

            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // Validate that order_id is provided in query parameters
            $request->validate([
                'order_id' => 'required|integer|exists:orders,order_id'
            ]);

            // Fetch the specific order
            $order = Order::where('user_id', $loggedInUser->user_id)
                ->where('order_id', $request->query('order_id'))
                ->select(
                    'order_id',
                    'tracking_id',
                    'order_items',
                    'total',
                    'delivery_fee',
                    'grand_total',
                    'customer_name',
                    'address',
                    'phone',
                    'second_phone',
                    'status',
                    'order_status',
                    'order_date'
                ) // Add the columns you need
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Decode order items
            $orderItems = json_decode($order->order_items, true);
            $productIds = [];

            if (is_array($orderItems)) {
                foreach ($orderItems as $item) {
                    $productIds[] = $item['product_id'];
                }
            }

            // Remove duplicate product IDs
            $productIds = array_unique($productIds);

            // Fetch product details
            $products = Products::select(
                'product_id',
                'product_name',
                'product_brand',
                'product_price',
                'product_images',
                'product_discounted_price'
            )
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id'); // Store products as an associative array

            // Merge product details into order items
            if (is_array($orderItems)) {
                foreach ($orderItems as &$item) {
                    if (isset($products[$item['product_id']])) {
                        $product = $products[$item['product_id']]->toArray();

                        // Decode JSON and get the first image path
                        $images = json_decode($product['product_images'], true);
                        $product['product_image'] = isset($images[0]) ? $images[0] : null; // Store first image separately

                        // Remove full product_images JSON (optional)
                        unset($product['product_images']);

                        $item = array_merge($item, $product);
                    }
                }
                $order->order_items = $orderItems;
            }

            return response()->json([
                'success' => true,
                'message' => 'Order details fetched successfully',
                'order'   => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

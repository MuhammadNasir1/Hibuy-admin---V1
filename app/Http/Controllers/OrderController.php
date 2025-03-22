<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Products;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function GetOrderWithProducts($Order_id)
    {
        try {
            // Step 1: Get logged-in user details
            $userDetails = session('user_details');
            if (!$userDetails) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $userId = $userDetails['user_id'];

            // Step 2: Get seller_id
            $sellerId = Seller::where('user_id', $userId)->value('seller_id');
            if (!$sellerId) {
                return response()->json(['error' => 'Seller not found'], 404);
            }

            // Step 3: Get store_id for the seller
            $storeId = Store::where('seller_id', $sellerId)->value('store_id');
            if (!$storeId) {
                return response()->json(['error' => 'Store not found'], 404);
            }

            // Step 4: Get product IDs belonging to this store
            $productIds = Products::where('store_id', $storeId)->pluck('product_id')->toArray();

            // Step 5: Fetch the order with the specific Order_id
            $order = Order::select([
                'order_id',
                'user_id',
                'tracking_id',
                'order_items',
                'total',
                'delivery_fee',
                'customer_name',
                'phone',
                'address',
                'status',
                'order_status',
                'order_date'
            ])
                ->where('order_id', $Order_id) // Filter by specific order_id
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $orderItems = json_decode($order->order_items, true);

            // Filter only the products belonging to the logged-in user's store
            $filteredOrderItems = array_filter($orderItems, function ($item) use ($productIds) {
                return in_array($item['product_id'], $productIds);
            });

            // If no products remain after filtering, return an error
            if (empty($filteredOrderItems)) {
                return response()->json(['error' => 'No matching products found in this order'], 404);
            }

            // Fetch product details from the products table
            $productDetails = Products::whereIn('product_id', array_column($filteredOrderItems, 'product_id'))
                ->select('product_id', 'product_name', 'product_brand', 'product_images', 'is_boosted')
                ->get()
                ->mapWithKeys(function ($product) {
                    // Decode product images JSON and extract the first image
                    $images = json_decode($product->product_images, true);
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;

                    return [$product->product_id => [
                        'product_name' => $product->product_name,
                        'product_brand' => $product->product_brand,
                        'product_image' => $firstImage, // Store only the first image
                        'is_boosted' => $product->is_boosted,
                    ]];
                });

            // Merge product details into order items
            $mergedOrderItems = array_map(function ($item) use ($productDetails) {
                if (isset($productDetails[$item['product_id']])) {
                    return array_merge($item, $productDetails[$item['product_id']]);
                }
                return $item;
            }, $filteredOrderItems);

            // Calculate grand total from merged order items
            $grandTotal = array_sum(array_column($mergedOrderItems, 'price'));

            // Prepare response data
            $response = [
                'order_id'      => $order->order_id,
                'tracking_id'   => $order->tracking_id,
                'customer_name' => $order->customer_name,
                'phone'         => $order->phone,
                'address'       => $order->address,
                'status'        => $order->status,
                'order_status'  => $order->order_status,
                'order_date'    => $order->order_date,
                'total'         => $grandTotal,
                'delivery_fee'  => $order->delivery_fee,
                'grand_total'   => $grandTotal + (float) $order->delivery_fee,
                'order_items'   => array_values($mergedOrderItems),
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function GetOrders()
    {
        try {
            // Step 1: Get logged-in user details
            $userDetails = session('user_details');
            if (!$userDetails) {
                return redirect()->back()->with('error', 'User not authenticated');
            }

            $userId = $userDetails['user_id'];

            // Step 2: Get seller_id
            $sellerId = Seller::where('user_id', $userId)->value('seller_id');
            if (!$sellerId) {
                return redirect()->back()->with('error', 'Seller not found');
            }

            // Step 3: Get store_id for the seller
            $storeId = Store::where('seller_id', $sellerId)->value('store_id');
            if (!$storeId) {
                return redirect()->back()->with('error', 'Store not found');
            }

            // Step 4: Get product IDs belonging to this store
            $productIds = Products::where('store_id', $storeId)->pluck('product_id')->toArray();

            // Step 5: Get only selected columns from the orders table
            $orders = Order::select([
                'order_id',
                'user_id',
                'tracking_id',
                'order_items',
                'total',
                'delivery_fee',
                'customer_name',
                'phone',
                'address',
                'status',
                'order_status',
                'order_date'
            ])
                ->get()
                ->map(function ($order) use ($productIds) {
                    $orderItems = json_decode($order->order_items, true);

                    // Filter only the products belonging to the logged-in user's store
                    $filteredOrderItems = array_filter($orderItems, function ($item) use ($productIds) {
                        return in_array($item['product_id'], $productIds);
                    });

                    // If no products remain after filtering, exclude this order
                    if (empty($filteredOrderItems)) {
                        return null;
                    }

                    // Calculate grand total from filtered order items
                    $grandTotal = array_sum(array_column($filteredOrderItems, 'price'));

                    // Convert back to array format
                    $order->order_items = array_values($filteredOrderItems);
                    $order->grand_total = $grandTotal; // Assign calculated grand total

                    return $order;
                })->filter(); // Remove null values

            // Return filtered orders to the view
            return view('pages.Orders', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Courier;
use App\Models\Products;
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
            $userRole = $userDetails['user_role']; // e.g. "admin" or "seller"

            // Step 2: Fetch the order with the specific Order_id
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
                ->where('order_id', $Order_id)
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $orderItems = json_decode($order->order_items, true);

            if ($userRole === 'admin') {
                // Admin: Don't filter order items
                $filteredOrderItems = $orderItems;
            } else {
                // Seller: Continue with filtering
                $sellerId = Seller::where('user_id', $userId)->value('seller_id');
                if (!$sellerId) {
                    return response()->json(['error' => 'Seller not found'], 404);
                }

                $storeId = Store::where('seller_id', $sellerId)->value('store_id');
                if (!$storeId) {
                    return response()->json(['error' => 'Store not found'], 404);
                }

                $productIds = Products::where('store_id', $storeId)->pluck('product_id')->toArray();

                $filteredOrderItems = array_filter($orderItems, function ($item) use ($productIds) {
                    return in_array($item['product_id'], $productIds);
                });

                if (empty($filteredOrderItems)) {
                    return response()->json(['error' => 'No matching products found in this order'], 404);
                }
            }

            // Step 3: Fetch product details from the products table
            $productDetails = Products::whereIn('product_id', array_column($filteredOrderItems, 'product_id'))
                ->select('product_id', 'product_name', 'product_brand', 'product_images', 'is_boosted')
                ->get()
                ->mapWithKeys(function ($product) {
                    $images = json_decode($product->product_images, true);
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;

                    return [$product->product_id => [
                        'product_name'  => $product->product_name,
                        'product_brand' => $product->product_brand,
                        'product_image' => $firstImage,
                        'is_boosted'    => $product->is_boosted,
                    ]];
                });

            // Step 4: Merge product details into order items
            $mergedOrderItems = array_map(function ($item) use ($productDetails) {
                if (isset($productDetails[$item['product_id']])) {
                    return array_merge($item, $productDetails[$item['product_id']]);
                }
                return $item;
            }, $filteredOrderItems);

            // Step 5: Calculate totals
            $grandTotal = array_sum(array_column($mergedOrderItems, 'price'));

            // Step 6: Prepare response
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
            // Step 7: Fetch all couriers for dropdown
            $response['couriers'] = Courier::select('courier_id', 'courier_name')->get();

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
            $userRole = $userDetails['user_role']; // Assuming 'admin' or 'seller'

            // Step 2: If admin, fetch all orders with products included
            if ($userRole === 'admin') {
                $productIds = Products::pluck('product_id')->toArray(); // All product IDs

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

                        // Filter items that exist in all products
                        $filteredOrderItems = array_filter($orderItems, function ($item) use ($productIds) {
                            return in_array($item['product_id'], $productIds);
                        });

                        if (empty($filteredOrderItems)) {
                            return null;
                        }

                        $grandTotal = array_sum(array_column($filteredOrderItems, 'price'));

                        $order->order_items = array_values($filteredOrderItems);
                        $order->grand_total = $grandTotal;

                        return $order;
                    })->filter();

                return view('pages.Orders', compact('orders'));
            }

            // Step 3: If not admin, treat as seller
            $sellerId = Seller::where('user_id', $userId)->value('seller_id');
            if (!$sellerId) {
                return redirect()->back()->with('error', 'Seller not found');
            }

            $storeId = Store::where('seller_id', $sellerId)->value('store_id');
            if (!$storeId) {
                return redirect()->back()->with('error', 'Store not found');
            }

            $productIds = Products::where('store_id', $storeId)->pluck('product_id')->toArray();

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

                    $filteredOrderItems = array_filter($orderItems, function ($item) use ($productIds) {
                        return in_array($item['product_id'], $productIds);
                    });

                    if (empty($filteredOrderItems)) {
                        return null;
                    }

                    $grandTotal = array_sum(array_column($filteredOrderItems, 'price'));

                    $order->order_items = array_values($filteredOrderItems);
                    $order->grand_total = $grandTotal;

                    return $order;
                })->filter();

            return view('pages.Orders', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

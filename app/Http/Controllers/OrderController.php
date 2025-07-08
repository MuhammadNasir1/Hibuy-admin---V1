<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Courier;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
                'courier_id',
                'tracking_number',
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
                'selected_courier_id' => $order->courier_id ?? null,
                'tracking_number' => $order->tracking_number ?? null,
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
                    ->orderBy('order_id', 'desc') // Added order by
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
                ->orderBy('order_id', 'desc') // Added order by
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



    public function updateOrderStatus(Request $request)
    {

        if (session('user_details.user_role') === 'admin') {
            $request->validate([
                'order_id' => 'required|exists:orders,order_id',
                'order_status' => 'required|string',
                'courier_id' => 'required|exists:couriers,courier_id',
                'tracking_number' => 'required|string|max:255',
                'delivery_status' => 'nullable|string',
                'product_id' => 'nullable|integer',
                'status_video' => 'nullable|file|mimes:mp4,avi,mov,webm|max:20480',
            ]);
        } else {
            $request->validate([
                'order_id' => 'required|exists:orders,order_id',
                'order_status' => 'nullable|string',
                'courier_id' => 'nullable|exists:couriers,courier_id',
                'tracking_number' => 'nullable|string|max:255',
                'delivery_status' => 'required|string',
                'product_id' => 'required|integer',
                'status_video' => 'required|file|mimes:mp4,avi,mov,webm|max:20480',
            ]);
        }


        $order = Order::find($request->order_id);

        // Admin update
        if ($request->filled('order_status')) {
            $order->order_status = $request->order_status;
            $order->courier_id = $request->courier_id;
            $order->tracking_number = $request->filled('tracking_number') ? $request->tracking_number : '';
        }

        //  Seller update (update status of a product inside order_items JSON)


        if ($request->filled('delivery_status')) {
            $orderItems = json_decode($order->order_items, true);
            $videoPath = null;

            // Upload the video once if provided
            if ($request->hasFile('status_video')) {
                $video = $request->file('status_video');
                $videoPath = $video->store('orders', 'public');
            }

            foreach ($orderItems as &$item) {
                // Fetch product to check seller ownership
                $product = Products::find($item['product_id']);

                if ($product && $product->seller_id == session('user_details.seller_id')) {
                    $item['delivery_status'] = $request->delivery_status;

                    // Set video if uploaded
                    if ($videoPath) {
                        if (!empty($item['status_video']) && Storage::disk('public')->exists($item['status_video'])) {
                            Storage::disk('public')->delete($item['status_video']);
                        }
                        $item['status_video'] = $videoPath;
                    }
                }
            }

            $order->order_items = json_encode($orderItems);
        }

        if ($request->filled('order_status') && $request->order_status === 'shipped') {
            $orderItems = is_string($order->order_items)
                ? json_decode($order->order_items, true)
                : $order->order_items;

            foreach ($orderItems as $item) {
                $product = Products::find($item['product_id']);

                if ($product && $product->product_variation) {
                    $variations = json_decode($product->product_variation, true);

                    foreach ($variations as &$variation) {
                        $parentMatched = false;

                        // Match parent option
                        if (
                            isset($item['parent_option']['value'], $variation['parentname']) &&
                            $variation['parentname'] === $item['parent_option']['value']
                        ) {
                            $variation['parentstock'] = max(0, $variation['parentstock'] - $item['quantity']);
                            $parentMatched = true;
                        }

                        // Match child option
                        if (!empty($variation['children']) && isset($item['child_option']['value'])) {
                            foreach ($variation['children'] as &$child) {
                                if (isset($child['name']) && $child['name'] === $item['child_option']['value']) {
                                    $child['stock'] = max(0, $child['stock'] - $item['quantity']);
                                    break;
                                }
                            }
                        }

                        // Optional: break loop if parent matched
                        if ($parentMatched) {
                            break;
                        }
                    }

                    // Save updated variation back to product
                    $product->product_variation = json_encode($variations);
                    $product->save();
                }
            }
        }

        $order->save();

        return response()->json(['message' => 'Order updated successfully']);
    }
}

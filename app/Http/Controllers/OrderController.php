<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\Seller;
use App\Models\RiderModel;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EmailController;
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
                'rider_id',
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

            // Step 3: Fetch product details from the products table with seller information
            $productDetails = Products::whereIn('product_id', array_column($filteredOrderItems, 'product_id'))
                ->with(['store.seller.user'])
                ->select('product_id', 'product_name', 'product_brand', 'product_images', 'is_boosted', 'store_id')
                ->get()
                ->mapWithKeys(function ($product) {
                    $images = json_decode($product->product_images, true);
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;

                    // Get seller information (keys align with KYC JSON schema)
                    $sellerInfo = null;
                    if ($product->store && $product->store->seller && $product->store->seller->user) {
                        $personalInfo = json_decode($product->store->seller->personal_info, true) ?? [];
                        $storeInfo = json_decode($product->store->store_info, true) ?? [];

                        $sellerInfo = [
                            'seller_name' => $personalInfo['full_name'] ?? $product->store->seller->user->user_name ?? 'N/A',
                            'seller_email' => $personalInfo['email'] ?? $product->store->seller->user->user_email ?? 'N/A',
                            'seller_phone' => $personalInfo['phone_no'] ?? $personalInfo['phone'] ?? 'N/A',
                            'store_name' => $storeInfo['store_name'] ?? 'N/A',
                            'store_address' => $storeInfo['address'] ?? $storeInfo['store_address'] ?? 'N/A',
                        ];

                        // Debug log
                        \Log::info('Seller info for product ' . $product->product_id . ':', $sellerInfo);
                    } else {
                        \Log::info('No seller info found for product ' . $product->product_id);
                    }

                    return [
                        $product->product_id => [
                            'product_name' => $product->product_name,
                            'product_brand' => $product->product_brand,
                            'product_image' => $firstImage,
                            'is_boosted' => $product->is_boosted,
                            'seller_info' => $sellerInfo,
                        ]
                    ];
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
                'order_id' => $order->order_id,
                'tracking_id' => $order->tracking_id,
                'customer_name' => $order->customer_name,
                'phone' => $order->phone,
                'address' => $order->address,
                'status' => $order->status,
                'order_status' => $order->order_status,
                'order_date' => $order->order_date,
                'total' => $grandTotal,
                'delivery_fee' => $order->delivery_fee,
                'grand_total' => $grandTotal + (float) $order->delivery_fee,
                'order_items' => array_values($mergedOrderItems),
                'selected_rider_id' => $order->rider_id ?? null,
                'tracking_number' => $order->tracking_number ?? null,
            ];
            // Step 7: Fetch all riders for dropdown
            $response['riders'] = RiderModel::select('id', 'rider_name', 'vehicle_type')->get();

            // Step 8: Add rider details to response if rider is assigned
            if ($order->rider_id) {
                $rider = RiderModel::select('id', 'rider_name', 'rider_email', 'phone', 'vehicle_type', 'vehicle_number', 'city')
                    ->where('id', $order->rider_id)
                    ->first();
                $response['rider_details'] = $rider;
            } else {
                $response['rider_details'] = null;
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function GetOrders()
    {
        try {
            // 1. Authentication and user role check
            $userDetails = session('user_details');
            if (!$userDetails) {
                return redirect()->back()->with('error', 'User not authenticated');
            }

            $userId = $userDetails['user_id'];
            $userRole = $userDetails['user_role']; // 'admin' or 'seller'

            // 2. Determine view type
            $routeName = optional(request()->route())->getName();
            $isLedgerView = in_array($routeName, ['sellerReport', 'orders-ledger']);

            // 3. Date filtering setup
            $today = now()->format('Y-m-d');
            $fromDate = request('from_date');
            $toDate = request('to_date');

            if ($isLedgerView && !$fromDate && !$toDate) {
                $fromDate = $today;
                $toDate = $today;
            }

            if ($fromDate && $toDate && $toDate < $fromDate) {
                [$fromDate, $toDate] = [$toDate, $fromDate];
            }

            // 4. Base query
            $query = Order::with('rider:id,rider_name,vehicle_type')
                ->orderBy('order_id', 'desc')
                ->select([
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
                    'rider_id',
                    'tracking_number',
                    'order_date',
                ]);

            // 5. Date filtering
            if ($fromDate && $toDate) {
                if ($fromDate === $toDate) {
                    $query->whereRaw("STR_TO_DATE(order_date, '%b %e, %Y') = ?", [$fromDate]);
                } else {
                    $query->whereRaw("STR_TO_DATE(order_date, '%b %e, %Y') BETWEEN ? AND ?", [$fromDate, $toDate]);
                }
            }

            // 6. Role-specific processing
            if ($userRole === 'admin') {
                $selectedStoreId = request('store_id');

                $orders = $query->get()->map(function ($order) use ($selectedStoreId) {
                    $orderItems = json_decode($order->order_items, true) ?? [];

                    // If store is selected, filter items by store
                    if ($selectedStoreId) {
                        $filteredItems = [];
                        foreach ($orderItems as $item) {
                            $product = Products::with('store')->find($item['product_id'] ?? null);
                            if ($product && $product->store_id == $selectedStoreId) {
                                $filteredItems[] = $item;
                                $storeInfo = json_decode($product->store->store_info, true) ?? [];
                                $order->store_name_for_list = $storeInfo['store_name'] ?? null;
                                $order->store_id = $product->store_id;
                            }
                        }
                        $orderItems = $filteredItems;
                    }

                    $order->order_items = $orderItems;

                    // If no items left after filtering, drop this order
                    if (empty($orderItems)) {
                        return null;
                    }

                    // Seller info (only first product used for metadata)
                    if (!empty($orderItems)) {
                        $firstProductId = $orderItems[0]['product_id'] ?? null;
                        if ($firstProductId) {
                            $product = Products::with(['store.seller.user'])->find($firstProductId);
                            if ($product && $product->store && $product->store->seller) {
                                $personalInfo = json_decode($product->store->seller->personal_info, true) ?? [];
                                $order->seller_name_for_list = $personalInfo['full_name'] ??
                                    $product->store->seller->user->user_name ?? 'N/A';
                                $order->seller_phone_for_list = $personalInfo['phone_no'] ??
                                    $personalInfo['phone'] ?? 'N/A';
                            }
                        }
                    }

                    // Totals based on filtered items
                    $productTotal = array_sum(array_map(function ($item) {
                        return ($item['quantity'] ?? 1) * ($item['price'] ?? 0);
                    }, $orderItems));

                    $order->grand_total = $productTotal + (float) $order->delivery_fee;
                    $order->grand_discount = $productTotal * 0.05;
                    $order->net_total = $order->grand_total - $order->grand_discount;

                    return $order;
                })->filter();

                // Fetch all stores for dropdown
                $stores = DB::table('stores')->get()->map(function ($store) {
                    $info = json_decode($store->store_info, true);
                    $store->store_name = $info['store_name'] ?? 'Unnamed Store';
                    return $store;
                });
            } else {
                $sellerId = Seller::where('user_id', $userId)->value('seller_id');
                if (!$sellerId) {
                    return redirect()->back()->with('error', 'Seller not found');
                }

                $storeId = Store::where('seller_id', $sellerId)->value('store_id');
                $productIds = Products::where('store_id', $storeId)->pluck('product_id')->toArray();

                $orders = $query->get()->map(function ($order) use ($productIds) {
                    $orderItems = json_decode($order->order_items, true) ?? [];
                    $filteredItems = array_filter($orderItems, function ($item) use ($productIds) {
                        return in_array($item['product_id'] ?? null, $productIds);
                    });

                    if (empty($filteredItems)) {
                        return null;
                    }

                    $order->order_items = array_values($filteredItems);

                    $productTotal = array_sum(array_map(function ($item) {
                        return ($item['quantity'] ?? 1) * ($item['price'] ?? 0);
                    }, $filteredItems));

                    $order->grand_total = $productTotal + (float) $order->delivery_fee;
                    $order->grand_discount = $productTotal * 0.05;
                    $order->net_total = $order->grand_total - $order->grand_discount;

                    return $order;
                })->filter();

                $stores = null; // sellers don’t get all stores
            }

            // 7. View data
            $viewData = [
                'orders' => $orders,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'isLedgerView' => $isLedgerView,
                'defaultDate' => $today,
                'stores' => $stores, // ✅ pass stores to view (null if seller)
            ];

            return $isLedgerView
                ? view('pages.orders_ledger', $viewData)
                : view('pages.Orders', $viewData);

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
                'rider_id' => 'required|exists:riders,id',
                'tracking_number' => 'required|string|max:255',
                'delivery_status' => 'nullable|string',
                'product_id' => 'nullable|integer',
                'status_video' => 'nullable|file|mimes:mp4,avi,mov,webm|max:20480',
            ]);
        } else {
            $request->validate([
                'order_id' => 'required|exists:orders,order_id',
                'order_status' => 'nullable|string',
                'rider_id' => 'nullable|exists:riders,id',
                'tracking_number' => 'nullable|string|max:255',
                'delivery_status' => 'required|string',
                'product_id' => 'required|integer',
                'status_video' => 'required|file|mimes:mp4,avi,mov,webm|max:20480',
            ]);
        }

        $order = Order::find($request->order_id);

        // --- Admin update ---
        if ($request->filled('order_status')) {
            $order->order_status = $request->order_status;
            $order->rider_id = $request->rider_id;
            $order->tracking_number = $request->filled('tracking_number') ? $request->tracking_number : '';
        }

        // --- Seller update ---
        if ($request->filled('delivery_status')) {
            $orderItems = json_decode($order->order_items, true);
            $videoPath = null;

            if ($request->hasFile('status_video')) {
                $video = $request->file('status_video');
                $videoPath = $video->store('orders', 'public');
            }

            foreach ($orderItems as &$item) {
                $product = Products::find($item['product_id']);

                if ($product && $product->seller_id == session('user_details.seller_id')) {
                    $item['delivery_status'] = $request->delivery_status;
                    $item['order_weight'] = $request->weight_admin;
                    $item['order_size'] = $request->size_admin;

                    if ($videoPath) {
                        if (!empty($item['status_video']) && Storage::disk('public')->exists($item['status_video'])) {
                            Storage::disk('public')->delete($item['status_video']);
                        }
                        $item['status_video'] = $videoPath;
                    }

                    // ✅ Send Email to Admin about status update
                    $sellerInfo = json_decode($product->personal_info, true);
                    $sellerName = $sellerInfo['name'] ?? '';
                    $status = ucfirst($request->delivery_status);

                    $subject = "Order #{$order->order_id} - Product Status Updated";
                    $body = "Hello Admin,<br><br>"
                        . "Seller <strong>{$sellerName}</strong> has updated the status of product "
                        . "<strong>{$product->product_name}</strong> in Order #{$order->order_id}.<br><br>"
                        . "New Delivery Status: <strong>{$status}</strong><br><br>"
                        . "Please review the update in the admin panel.";

                    // Use your existing email function

                    (new EmailController())->sendMail("info.arham.org@gmail.com", $subject, $body);
                }
            }

            $order->order_items = json_encode($orderItems);
        }

        // --- Reduce stock when shipped ---
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

                        if (
                            isset($item['parent_option']['value'], $variation['parentname']) &&
                            $variation['parentname'] === $item['parent_option']['value']
                        ) {
                            $variation['parentstock'] = max(0, $variation['parentstock'] - $item['quantity']);
                            $parentMatched = true;
                        }

                        if (!empty($variation['children']) && isset($item['child_option']['value'])) {
                            foreach ($variation['children'] as &$child) {
                                if (isset($child['name']) && $child['name'] === $item['child_option']['value']) {
                                    $child['stock'] = max(0, $child['stock'] - $item['quantity']);
                                    break;
                                }
                            }
                        }

                        if ($parentMatched) {
                            break;
                        }
                    }

                    $product->product_variation = json_encode($variations);
                    $product->save();
                }
            }
        }

        $order->save();

        return response()->json(['message' => 'Order updated successfully']);
    }

    public function printSlip($orderId)
    {
        // Get user role from session
        $userDetails = Session('user_details');
        $userRole = $userDetails['user_role'] ?? null;
        $isAdmin = $userRole === 'admin';
        $userStoreId = $userDetails['store_id'] ?? null;

        // Get the order with customer details
        $order = DB::table('orders')
            ->where('order_id', $orderId)
            ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Decode order_items JSON
        $orderItems = json_decode($order->order_items, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($orderItems)) {
            abort(500, 'Invalid order items format');
        }

        // Initialize variables
        $products = [];
        $storeIds = [];

        // Collect products and store IDs
        foreach ($orderItems as $item) {
            $productId = $item['product_id'] ?? null;
            if (!$productId) {
                continue;
            }

            // Fetch product details
            $product = DB::table('products')
                ->where('product_id', $productId)
                ->select('product_id', 'store_id', 'user_id', 'product_name', 'product_price', 'product_discounted_price')
                ->first();

            if ($product) {
                $storeIds[] = $product->store_id;

                $products[] = [
                    'product_id' => $product->product_id,
                    'product_name' => $item['product_name'] ?? $product->product_name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'store_id' => $product->store_id,
                ];
            }
        }

        if (empty($products)) {
            abort(404, 'No products found');
        }

        // Fetch store details
        $storeIds = array_unique($storeIds);
        $sellers = DB::table('stores')
            ->whereIn('store_id', $storeIds)
            ->select(
                'store_id',
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(store_info, "$.store_name")) AS store_name'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(store_info, "$.phone_no")) AS store_phone'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(store_info, "$.email")) AS store_email'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(store_info, "$.address")) AS store_address')
            )
            ->get()
            ->keyBy('store_id')
            ->toArray();

        // Prepare the response data - consistent structure for both admin and non-admin
        $data = [
            'order' => $order,
            'isAdmin' => $isAdmin,
            'products' => [],  // This will contain all products for the current view
            'seller' => null,  // This will contain the store details for non-admin
            'grouped_products' => []  // This will contain grouped products for admin
        ];

        // For non-admin users, filter products by their store
        if (!$isAdmin) {
            if (!$userStoreId) {
                abort(403, 'You don\'t have permission to view this order');
            }

            $filteredProducts = array_filter($products, function ($product) use ($userStoreId) {
                return $product['store_id'] == $userStoreId;
            });

            if (empty($filteredProducts)) {
                abort(403, 'No products found for your store in this order');
            }

            $data['products'] = array_values($filteredProducts);
            $data['seller'] = $sellers[$userStoreId] ?? null;
        }
        // For admin users, group products by store
        else {
            foreach ($products as $product) {
                $storeId = $product['store_id'];

                if (!isset($data['grouped_products'][$storeId])) {
                    $data['grouped_products'][$storeId] = [
                        'store' => $sellers[$storeId] ?? null,
                        'products' => []
                    ];
                }

                $data['grouped_products'][$storeId]['products'][] = $product;
            }
        }

        return view('pages.slip', $data);
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\EmailController;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Store;
use App\Models\Reviews;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            // Ensure the user is authenticated
            $loggedInUser = Auth::user();
            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // Validate request data
            $validatedData = $request->validate([
                'items' => 'required|array',
                'total' => 'required|numeric',
                'delivery_fee' => 'nullable|numeric',
                'grand_total' => 'required|numeric',
                'address' => 'required|array',
            ]);

            // Generate a tracking ID
            $trackingId = 'TRK-' . strtoupper(uniqid());
            $items = is_array($validatedData['items']) ? $validatedData['items'] : json_decode($validatedData['items'], true);

            // Create the order
            $order = Order::create([
                'user_id' => $loggedInUser->user_id,
                'tracking_id' => $trackingId,
                'order_items' => json_encode($items),
                'total' => $validatedData['total'],
                'delivery_fee' => $validatedData['delivery_fee'] ?? 0,
                'grand_total' => $validatedData['grand_total'],
                'customer_name' => $validatedData['address']['name'],
                'phone' => $validatedData['address']['phone'],
                'address' => $validatedData['address']['address'],
                'second_phone' => $validatedData['address']['second_phone'] ?? null,
                'order_date' => now()->format('M d, Y'),
                'status' => 'pending',
                'order_status' => 'order_placed',
            ]);

            // Decode order_items
            $orderItems = json_decode($order->order_items, true);

            // Extract product IDs
            $productIds = array_column($orderItems, 'product_id');
            $products = Products::select(
                'product_id',
                'product_name',
                'product_brand',
                'product_price',
                'product_discounted_price',
                'user_id'
            )
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            // Attach product details to each order item
            foreach ($orderItems as &$item) {
                $item['product_details'] = $products[$item['product_id']] ?? null;
            }
            $order->order_items = $orderItems;

            /**
             * ---------------------
             * EMAIL 1: Customer
             * ---------------------
             */
            $customerSubject = "Order Confirmation - Tracking ID: {$trackingId}";

            // Build order items table for customer
            $customerTable = "
                <table border='1' cellpadding='8' cellspacing='0' 
                       style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;'>
                    <thead style='background:#f4f4f4;'>
                        <tr>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($orderItems as $item) {
                $p = $item['product_details'];
                $customerTable .= "
                        <tr>
                            <td>{$p->product_name}</td>
                            <td>{$p->product_brand}</td>
                            <td>{$item['quantity']}</td>
                            <td>{$p->product_price}</td>
                        </tr>";
            }
            $customerTable .= "
                    </tbody>
                </table>";

            $customerBody = "
                <h3>Hello {$validatedData['address']['name']},</h3>
                <p>Thank you for your order!</p>
                <p><b>Tracking ID:</b> {$trackingId}<br>
                   <b>Order Date:</b> " . now()->format('M d, Y') . "</p>
                <h4>Order Details:</h4>
                {$customerTable}
                <p><b>Total:</b> {$validatedData['total']}<br>
                   <b>Delivery Fee:</b> {$validatedData['delivery_fee']}<br>
                   <b>Grand Total:</b> {$validatedData['grand_total']}</p>
                <p>We will notify you once your order is shipped.</p>
                <p>Thank you for shopping with us!</p>
            ";

            (new EmailController)->sendMail($loggedInUser->user_email, $customerSubject, $customerBody);

            /**
             * ---------------------
             * EMAIL 2: Sellers
             * ---------------------
             */

            // Group products by seller_id
            $sellerProducts = [];
            foreach ($orderItems as $item) {
                $p = $item['product_details'];
                if ($p) {
                    $sellerProducts[$p->user_id][] = $p;
                }
            }

            $sellers = Seller::whereIn('user_id', array_keys($sellerProducts))->get()->keyBy('user_id');

            // return response()->json(['seller', $sellers]);
            foreach ($sellerProducts as $sellerId => $productsList) {
                if (!isset($sellers[$sellerId])) {
                    continue;
                }

                $seller = $sellers[$sellerId];

                // Decode seller personal info
                $personalInfo = json_decode($seller->personal_info, true);
                $sellerEmail = $personalInfo['email'] ?? null;
                $sellerName = $personalInfo['full_name'] ?? 'Seller';

                if (!$sellerEmail) {
                    continue; // Skip if no email found
                }

                // Build seller's product table
                $sellerTable = "
                    <table border='1' cellpadding='8' cellspacing='0' 
                           style='border-collapse:collapse;width:100%;font-family:Arial,sans-serif;'>
                        <thead style='background:#f4f4f4;'>
                            <tr>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>";

                foreach ($productsList as $prod) {
                    $sellerTable .= "
                            <tr>
                                <td>{$prod->product_name}</td>
                                <td>{$prod->product_brand}</td>
                                <td>{$prod->product_price}</td>
                            </tr>";
                }
                $sellerTable .= "
                        </tbody>
                    </table>";

                $sellerSubject = "New Order Received - Tracking ID: {$trackingId}";
                $sellerBody = "
                    <h3>Hello {$sellerName},</h3>
                    <p>You have received a new order.</p>
                    <p><b>Customer:</b> {$validatedData['address']['name']}<br>
                       <b>Phone:</b> {$validatedData['address']['phone']}<br>
                       <b>Address:</b> {$validatedData['address']['address']}</p>
                    <h4>Ordered Items:</h4>
                    {$sellerTable}
                    <p>Please process the order promptly.</p>
                    <p>Thank you.</p>
                ";

                (new EmailController)->sendMail($sellerEmail, $sellerSubject, $sellerBody);
            }


            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order
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
                ->orderBy('order_id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Orders fetched successfully',
                'orders' => $orders
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
                )
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Ensure second_phone is null if empty
            $order->second_phone = !empty($order->second_phone) ? $order->second_phone : null;

            // Decode order items
            $orderItems = json_decode($order->order_items, true);
            $productIds = [];

            if (is_array($orderItems)) {
                foreach ($orderItems as $item) {
                    $productIds[] = $item['product_id'];
                }
            }

            $productIds = array_unique($productIds);

            // Fetch reviewed product IDs by this user for this order
            $reviewedProductIds = Reviews::where('user_id', $loggedInUser->user_id)
                ->where('order_id', $order->order_id)
                ->pluck('product_id')
                ->toArray();

            // Fetch product details
            $products = Products::select(
                'product_id',
                'store_id',
                'product_name',
                'product_images',
                'product_discounted_price'
            )
                ->whereIn('product_id', $productIds)
                ->get()
                ->keyBy('product_id');

            // Fetch store details
            $storeIds = $products->pluck('store_id')->unique()->toArray();

            $stores = Store::select('store_id', 'store_info', 'store_profile_detail')
                ->whereIn('store_id', $storeIds)
                ->get()
                ->keyBy('store_id');

            // Merge product and store details into order items dynamically
            if (is_array($orderItems)) {
                foreach ($orderItems as &$item) {
                    if (isset($products[$item['product_id']])) {
                        $product = $products[$item['product_id']]->toArray();

                        // Decode images and get the first image
                        $images = json_decode($product['product_images'], true);
                        $product['product_image'] = isset($images[0]) ? $images[0] : null;
                        unset($product['product_images']);

                        // Inject product data
                        foreach ($product as $key => $value) {
                            $item[$key] = $value;
                        }

                        // Set review status
                        $item['has_review'] = in_array($item['product_id'], $reviewedProductIds);

                        // Extract variant info (parent_option/child_option)
                        $item['parentOptionName'] = $item['parent_option']['name'] ?? null;
                        $item['selectedParentOption'] = $item['parent_option']['value'] ?? null;

                        $item['childrenOptionName'] = $item['child_option']['name'] ?? null;
                        $item['selectedChildrenOption'] = $item['child_option']['value'] ?? null;

                        // Extract store name
                        $storeName = null;
                        if (isset($stores[$product['store_id']])) {
                            $store = $stores[$product['store_id']];
                            $storeDetails = json_decode($store->store_profile_detail, true);
                            $storeName = $storeDetails['store_name'] ?? null;

                            if (!$storeName) {
                                $storeInfo = json_decode($store->store_info, true);
                                $storeName = $storeInfo['store_name'] ?? null;
                            }
                        }

                        $item['store_name'] = $storeName;
                    }
                }

                $order->order_items = $orderItems;
            }

            return response()->json([
                'success' => true,
                'message' => 'Order details fetched successfully',
                'order' => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder(Request $request)
    {
        try {
            $loggedInUser = Auth::user();

            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            $request->validate([
                'order_id' => 'required|integer',
                'cancel_note' => 'required|string|max:500',
            ]);

            $order = Order::where('order_id', $request->order_id)
                ->where('user_id', $loggedInUser->user_id) // ensure this user owns the order
                ->where('order_status', '!=', 'cancelled') // ensure this user owns the order
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Update order status and note
            $order->order_status = 'cancelled'; // or whatever status you use
            $order->status = 'cancelled'; // or whatever status you use
            $order->cancel_note = $request->cancel_note;
            $order->canceled_at = now();
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                // 'order'   => $order
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createReturn(Request $request)
    {
        try {
            $loggedInUser = Auth::user();

            if (!$loggedInUser) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }
            $products = $request->input('products');

            // If products is a JSON string → decode it
            if (is_string($products)) {
                $products = json_decode($products, true);
            }

            // If still not array → throw error
            if (!is_array($products)) {
                return response()->json(['success' => false, 'message' => 'Invalid products format'], 422);
            }

            // Merge into request so validator sees array
            $request->merge(['products' => $products]);


            // ✅ Validate input
            $validated = $request->validate([
                'order_id' => 'required|integer',
                'return_reason' => 'required|string|max:500',
                'return_note' => 'nullable|string|max:1000',
                'return_images' => 'nullable|array',
                'return_images.*' => 'image|max:2048',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|integer',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
                'products.*.product_name' => 'required|string|max:255',
                'products.*.image' => 'required|url',
                'products.*.delivery_status' => 'nullable|string',
                'products.*.status_video' => 'nullable|string',
                'products.*.parent_option' => 'nullable|array',
                'products.*.child_option' => 'nullable|array',
            ]);

            // ✅ Check if order exists and is not cancelled
            $order = DB::table('orders')
                ->where('order_id', $validated['order_id'])
                ->where('user_id', $loggedInUser->user_id)
                ->where('order_status', '!=', 'cancelled')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or has been cancelled'
                ], 404);
            }

            // ✅ Upload and store return images (if any)
            $returnImagePaths = [];
            if (!empty($validated['return_images'])) {
                foreach ($validated['return_images'] as $imageFile) {
                    $path = $imageFile->store('returns', 'public');
                    // Use full URL
                    $returnImagePaths[] = asset('storage/' . $path);
                }
            }

            // ✅ Calculate totals
            $returnTotal = 0;
            foreach ($validated['products'] as $product) {
                $returnTotal += $product['price'] * $product['quantity'];
            }

            $returnDeliveryFee = 0;
            $returnGrandTotal = $returnTotal + $returnDeliveryFee;

            // ✅ Save return in DB
            $returnId = DB::table('returns')->insertGetId([
                'order_id' => $validated['order_id'],
                'user_id' => $loggedInUser->user_id,
                'return_items' => json_encode($validated['products']),
                'return_total' => $returnTotal,
                'return_delivery_fee' => $returnDeliveryFee,
                'return_grand_total' => $returnGrandTotal,
                'return_status' => 'pending',
                'return_reason' => $validated['return_reason'],
                'return_note' => $validated['return_note'] ?? null,
                'return_address' => null,
                // 'return_courier' => null,
                'return_images' => !empty($returnImagePaths) ? json_encode($returnImagePaths) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Return request created successfully',
                'return_id' => $returnId
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

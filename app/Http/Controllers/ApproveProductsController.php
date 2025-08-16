<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Products;
use App\Models\Seller;
use Illuminate\Http\Request;


class ApproveProductsController extends Controller
{
    public function view()
    {
        $stores = Store::with([
            'user',
            'seller',
            'products' => function ($query) {
                $query->where(function ($q) {
                    $q->where('is_rejected', true)
                        ->orWhere('product_status', false);
                });
            },
        ])->get();
        // return response()->json($stores);
        return view('admin.approveProducts', compact('stores'));
    }

    public function approve(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer',
        ]);

        $productIds = $validated['product_ids'];

        // First update all products
        Products::whereIn('product_id', $productIds)
            ->update([
                'is_approved' => true,
                'is_rejected' => false,
                'product_status' => 1,
            ]);

        // Now fetch the updated products with their sellers
        $products = Products::whereIn('product_id', $productIds)->get();

        foreach ($products as $product) {
            $seller = Seller::where('user_id', $product->user_id)->first();

            if ($seller) {
                $sellerInfo = json_decode($seller->personal_info, true);

                if (!empty($sellerInfo['email'])) {
                    $sellerEmail = $sellerInfo['email'];
                    $sellerName = $sellerInfo['name'] ?? 'Seller';

                    $subject = "Your product has been approved ✅";
                    $body = "Dear {$sellerName},<br><br>"
                        . "Your product <strong>{$product->product_name}</strong> has been approved and is now live on the platform.<br><br>"
                        . "Thank you for using our marketplace!";

                    (new EmailController)->sendMail($sellerEmail, $subject, $body);
                }
            }
        }

        return response()->json([
            'success' => true,
            'updated_count' => count($products),
            'product_ids' => $productIds,
        ]);
    }




    public function reject(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'rejection_note' => 'required|string|max:1000',
        ]);

        // First fetch product model
        $product = Products::where('product_id', $validated['product_id'])->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Update product
        $product->update([
            'is_approved' => false,
            'is_rejected' => true,
            'product_status' => 0,
            'rejection_note' => $validated['rejection_note'],
        ]);

        // Now use product->user_id
        $seller = Seller::where('user_id', $product->user_id)->first();

        if ($seller) {
            $sellerInfo = json_decode($seller->personal_info, true);

            if (is_array($sellerInfo)) {
                $sellerEmail = $sellerInfo['email'] ?? null;
                $sellerName = $sellerInfo['name'] ?? 'Seller';

                if ($sellerEmail) {
                    $subject = "Your product has been rejected ❌";
                    $body = "Dear {$sellerName},<br><br>"
                        . "Unfortunately, your product <strong>{$product->product_name}</strong> has been rejected.<br><br>"
                        . "Reason: {$validated['rejection_note']}<br><br>"
                        . "Please review the note and resubmit your product after making the necessary changes.";
                    $mail = (new EmailController)->sendMail($sellerEmail, $subject, $body);
                }
            } else {
                \Log::error("Invalid or missing personal_info for product_id {$product->product_id}");
            }
        }

        return response()->json([
            'success' => true,
            'product_id' => $validated['product_id'],
        ]);
    }


}

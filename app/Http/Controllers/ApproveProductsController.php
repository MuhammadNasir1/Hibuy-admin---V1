<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Products;
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

        $updated = Products::whereIn('product_id', $productIds)
            ->update([
                'is_approved' => true,
                'is_rejected' => false,
                'product_status' => 1,
            ]);

        return response()->json([
            'success' => true,
            'updated_count' => $updated,
            'product_ids' => $productIds,
        ]);
    }

    public function reject(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'rejection_note' => 'required|string|max:1000',
        ]);

        $updated = Products::where('product_id', $validated['product_id'])
            ->update([
                'is_approved' => false,
                'is_rejected' => true,
                'product_status' => 0,
                'rejection_note' => $validated['rejection_note'],
            ]);

        return response()->json([
            'success' => (bool) $updated,
            'product_id' => $validated['product_id'],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function insertDetails(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' =>  'required|exists:users,user_id',
                'info_type' => 'required|string',
                'data' => 'required|json',
            ]);
            // $newData = $validatedData['info_type'];

            $seller = Seller::where('user_id', $validatedData['user_id'])->first();
            if (!$seller) {
                return response()->json(['success' => false, 'message' => "Seller Not Found"], 404);
            }

            $seller->$validatedData['info_type'] = json_encode($validatedData['data']);

            return response()->json(['success' => true, 'message' => "Data Inserted Successfully"], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}

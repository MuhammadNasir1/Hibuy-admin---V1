<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KYCController extends Controller
{

    public function kycData()
    {
        $sellers = Seller::join('users', 'seller.user_id', '=', 'users.user_id')
            ->select('seller.*', 'users.*')
            ->get()
            ->map(function ($seller) {
                $seller->submission_date = Carbon::parse($seller->updated_at)->format('Y-m-d');
                return $seller;
            });

        foreach ($sellers as $seller) {
            // JSON fields to check
            $jsonFields = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];

            $pendingCount = 0;
            $approvedCount = 0;
            $totalSteps = count($jsonFields); // Total steps

            foreach ($jsonFields as $field) {
                if (!empty($seller->$field)) {
                    $data = json_decode($seller->$field, true);
                    if (isset($data['status'])) {
                        if ($data['status'] == 'pending') {
                            $pendingCount++;
                        } elseif ($data['status'] == 'approved') {
                            $approvedCount++;
                        }
                    }
                }
            }

            // Store counts in the same seller object
            $seller->steps_progress = "$approvedCount/$totalSteps";
        }

        return view('admin.KYC', compact('sellers'));
    }


    public function kycDataSelect($id)
    {
        // Fetch all sellers
        $sellers = Seller::join('users', 'seller.user_id', '=', 'users.user_id')
            ->select('seller.*', 'users.*')
            ->get()
            ->map(function ($seller) {
                $seller->submission_date = Carbon::parse($seller->updated_at)->format('Y-m-d');
                return $seller;
            });

        // Check pending/approved counts
        foreach ($sellers as $seller) {
            $jsonFields = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];

            $pendingCount = 0;
            $approvedCount = 0;
            $totalSteps = count($jsonFields);

            foreach ($jsonFields as $field) {
                if (!empty($seller->$field)) {
                    $data = json_decode($seller->$field, true);
                    if (isset($data['status'])) {
                        if ($data['status'] == 'pending') {
                            $pendingCount++;
                        } elseif ($data['status'] == 'approved') {
                            $approvedCount++;
                        }
                    }
                }
            }

            $seller->steps_progress = "$approvedCount/$totalSteps";
        }

        // Fetch selected seller
        $selectedSeller = Seller::join('users', 'seller.user_id', '=', 'users.user_id')
            ->select('seller.*', 'users.*')
            ->where('seller_id', $id)
            ->first();

        if ($selectedSeller) {
            // Decode JSON fields
            $selectedSeller->personal_info = json_decode($selectedSeller->personal_info, true);
            $selectedSeller->store_info = json_decode($selectedSeller->store_info, true);
            $selectedSeller->documents_info = json_decode($selectedSeller->documents_info, true);
            $selectedSeller->bank_info = json_decode($selectedSeller->bank_info, true);
            $selectedSeller->business_info = json_decode($selectedSeller->business_info, true);
        }

        return response()->json([
            'sellers' => $sellers,
            'selectedSeller' => $selectedSeller
        ]);
    }

}

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
}

<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KYCController extends Controller
{

    public function kycData($type)
    {
        $jsonFields = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];

        $query = Seller::join('users', 'seller.user_id', '=', 'users.user_id')
            ->select('seller.*', 'users.*')
            ->where('users.user_role', $type);

        // Add whereNotNull for each JSON field
        foreach ($jsonFields as $field) {
            $query->whereNotNull("seller.$field");
        }

        $sellers = $query->get()->map(function ($seller) use ($jsonFields) {
            $seller->submission_date = Carbon::parse($seller->updated_at)->format('Y-m-d');

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
            return $seller;
        });

        return view('admin.KYC', compact('sellers', 'type'));
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
            $selectedSeller->current_seller = $selectedSeller->seller_id;
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

    public function approveKyc(Request $request)
    {
        try {
            $seller_id = $request->seller_id;
            $step = $request->step;

            $seller = Seller::find($seller_id);

            if (!$seller) {
                return response()->json(['message' => 'Seller not found'], 404);
            }

            $jsonColumns = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];
            $allStepsApproved = true;
            $isUpdated = false;
            $approvedReason = '';
            $approvedStepName = '';

            foreach ($jsonColumns as $column) {
                if (!empty($seller->$column)) {
                    $jsonData = json_decode($seller->$column, true);

                    if (isset($jsonData['step']) && $jsonData['step'] == $step) {
                        $jsonData['status'] = 'approved';

                        // Store the reason before clearing it
                        if (isset($jsonData['reason'])) {
                            $approvedReason = $jsonData['reason'];
                            $jsonData['reason'] = '';
                        }

                        // Store which step is approved (column name)
                        $approvedStepName = ucfirst(str_replace('_', ' ', $column));

                        $seller->$column = json_encode($jsonData);
                        $isUpdated = true;
                    }

                    if (isset($jsonData['status']) && $jsonData['status'] != 'approved') {
                        $allStepsApproved = false;
                    }
                }
            }

            if ($allStepsApproved) {
                $seller->status = 'approved';
            }

            if ($isUpdated) {
                $seller->save();

                // Send email
                $subject = "KYC Step Approved: {$approvedStepName}";
                $body = "Hello {$seller->name},\n\n" .
                    "Your KYC step '{$approvedStepName}' has been approved.\n" .
                    (!empty($approvedReason) ? "Reason: {$approvedReason}\n" : "") .
                    "\nThank you for your cooperation.";

                (new EmailController)->sendMail($seller->email, $subject, $body);

                return response()->json(['message' => 'KYC Approved successfully']);
            }

            return response()->json(['message' => 'No matching step found'], 400);
        } catch (\Exception $e) {
            return response()->json([
                "message" => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function rejectKyc(Request $request)
    {
        try {
            $seller_id = $request->seller_id;
            $step = $request->step;
            $reason = $request->reason;

            $seller = Seller::find($seller_id);

            if (!$seller) {
                return response()->json(['message' => 'Seller not found'], 404);
            }

            $jsonColumns = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];
            $allStepsRejected = true;
            $isUpdated = false;
            $rejectedStepName = '';

            foreach ($jsonColumns as $column) {
                if (!empty($seller->$column)) {
                    $jsonData = json_decode($seller->$column, true);

                    if (isset($jsonData['step']) && $jsonData['step'] == $step) {
                        $jsonData['status'] = 'rejected';
                        $jsonData['reason'] = $reason;

                        // Store step name for email
                        $rejectedStepName = ucfirst(str_replace('_', ' ', $column));

                        $seller->$column = json_encode($jsonData);
                        $isUpdated = true;
                    }

                    if (!isset($jsonData['status']) || $jsonData['status'] != 'rejected') {
                        $allStepsRejected = false;
                    }
                }
            }

            if ($allStepsRejected) {
                $seller->status = 'rejected';
            }

            if ($isUpdated) {
                $seller->save();

                // Send rejection email
                $subject = "KYC Step Rejected: {$rejectedStepName}";
                $body = "Hello {$seller->name},\n\n" .
                    "Your KYC step '{$rejectedStepName}' has been rejected.\n" .
                    "Reason: {$reason}\n\n" .
                    "Please review the feedback and resubmit the necessary details.\n\n" .
                    "Thank you.";

                (new EmailController)->sendMail($seller->email, $subject, $body);

                return response()->json(['message' => 'KYC Rejected successfully']);
            }

            return response()->json(['message' => 'No matching step found'], 400);
        } catch (\Exception $e) {
            return response()->json([
                "message" => 'Something went wrong',
                "error" => $e->getMessage()
            ], 500);
        }
    }







    public function kycView()
    {
        $user = session('user_details')['user_id'];
        $seller = Seller::where('user_id', $user)->first();

        $statusImages = [
            'personal_info' => asset('asset/kyc-pending.png'),
            'store_info' => asset('asset/kyc-pending.png'),
            'documents_info' => asset('asset/kyc-pending.png'),
            'bank_info' => asset('asset/kyc-pending.png'),
            'business_info' => asset('asset/kyc-pending.png'),
        ];

        $imageMap = [
            'pending' => asset('asset/kyc-pending.png'),
            'approved' => asset('asset/kyc-approve.png'),
            'rejected' => asset('asset/kyc-reject.png'),
        ];

        $jsonColumns = ['personal_info', 'store_info', 'documents_info', 'bank_info', 'business_info'];
        foreach ($jsonColumns as $column) {
            if (!empty($seller->$column)) {
                $jsonData = json_decode($seller->$column, true);
                if (!empty($jsonData['status']) && isset($imageMap[$jsonData['status']])) {
                    $statusImages[$column] = $imageMap[$jsonData['status']];
                }
            }
        }
        // Status check
        $kycStatus = $seller->status ?? 'pending'; // Default 'pending' if null

        // Button disable/enable logic
        $isDisabled = $kycStatus === 'pending';
        $imageSrc = $kycStatus === 'approved' ? asset('asset/kyc status (1).png') : asset('asset/kyc status.png');
        return view('Auth.ApproveKyc', compact('statusImages', 'kycStatus', 'isDisabled', 'imageSrc'));
    }
}

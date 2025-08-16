<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CreditRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CreditRequestController extends Controller
{
    // {
    //     $userId = session('user_details.user_id');
    //     $userRole = session('user_details.user_role');

    //     $query = DB::table('credit_request')
    //         ->join('users', 'credit_request.user_id', '=', 'users.user_id')
    //         ->select(
    //             'credit_request.*',
    //             'users.user_name as user_name',
    //             'users.created_at as user_joined_at'
    //         );

    //     if ($userRole === 'freelancer') {
    //         $query->where('credit_request.user_id', $userId);
    //     }

    //     $creditRequests = $query->get();

    //     return view('pages.CreditRequest', compact('creditRequests'));
    // }

    // public function index()
    // {
    //     $userId = session('user_details.user_id');
    //     $userRole = session('user_details.user_role');

    //     $query = DB::table('credit_request')
    //         ->join('users', 'credit_request.user_id', '=', 'users.user_id')
    //         ->select(
    //             'credit_request.*',
    //             'users.user_name as user_name',
    //             'users.created_at as user_joined_at'
    //         );

    //     // Apply filter if freelancer
    //     if ($userRole === 'freelancer') {
    //         $query->where('credit_request.user_id', $userId);
    //     }

    //     $creditRequests = $query->get();

    //     // Get request status counts
    //     $statusQuery = DB::table('credit_request');
    //     if ($userRole === 'freelancer') {
    //         $statusQuery->where('user_id', $userId);
    //     }

    //     $pendingCount = (clone $statusQuery)->where('request_status', 'pending')->count();
    //     $approvedCount = (clone $statusQuery)->where('request_status', 'approved')->count();
    //     $rejectedCount = (clone $statusQuery)->where('request_status', 'rejected')->count();

    //     return view('pages.CreditRequest', compact(
    //         'creditRequests',
    //         'pendingCount',
    //         'approvedCount',
    //         'rejectedCount'
    //     ));
    // }

    public function index()
    {
        $userId = session('user_details.user_id');
        $userRole = session('user_details.user_role');

        $query = DB::table('credit_request')
            ->join('users', 'credit_request.user_id', '=', 'users.user_id')
            ->select(
                'credit_request.*',
                'users.user_name as user_name',
                'users.created_at as user_joined_at'
            );

        // Apply filter if freelancer
        if ($userRole === 'freelancer') {
            $query->where('credit_request.user_id', $userId);
        }

        $creditRequests = $query->get();

        // Get request status counts
        $statusQuery = DB::table('credit_request');
        if ($userRole === 'freelancer') {
            $statusQuery->where('user_id', $userId);
        }

        $pendingCount = (clone $statusQuery)->where('request_status', 'pending')->count();
        $approvedCount = (clone $statusQuery)->where('request_status', 'approved')->count();
        $rejectedCount = (clone $statusQuery)->where('request_status', 'rejected')->count();
        $approvedTotalAmount = (clone $statusQuery)->where('request_status', 'approved')->sum('amount');
        $totalUsedAmount = (clone $statusQuery)
        ->where('request_status', 'approved')
        ->sum('credit_use');
        return view('pages.CreditRequest', compact(
            'creditRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'approvedTotalAmount',
            'totalUsedAmount'
        ));
    }

    public function show($id)
    {
        $creditRequest = DB::table('credit_request')
            ->join('users', 'credit_request.user_id', '=', 'users.user_id')
            ->where('credit_request.credit_id', $id)
            ->select(
                'credit_request.*',
                'users.user_name',
            )
            ->first();

        if (!$creditRequest) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json($creditRequest);
    }


    public function store(Request $request)
    {
        try {
            // $user_id = session('user_details.user_id');
            // Validate the request
            $validated = $request->validate([
                'amount' => 'required|numeric|min:100|max:1000000',
                'reason' => 'required|string|max:255',
                'user_id' => 'required',
            ]);
            // return $validated;

            // Create credit request
            $creditRequest = CreditRequest::create([
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'reason' => $validated['reason'],
                'request_status' => 'pending',
                'credit_use' => 0,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Credit request submitted successfully',
                'data' => $creditRequest
            ]);

        } catch (\Exception $e) {
            Log::error('Credit Request Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit credit request',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }




    public function updateStatus(Request $request)
    {
        // Validate input
        $request->validate([
            'credit_id' => 'required',
            'request_status' => 'required|string'
        ]);

        try {

            $creditRequest = CreditRequest::findOrFail($request->credit_id);
            $creditRequest->request_status = $request->request_status;
            $creditRequest->save();

            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // In case of error, return failure response
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function creditCount(){
        try {
            $userId = session('user_details.user_id');
            $userRole = session('user_details.user_role');

            $query = DB::table('credit_request');

            if ($userRole === 'freelancer') {
                $query->where('user_id', $userId);
            }

            $count = $query->where('request_status', 'pending')->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            Log::error('Credit Count Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to retrieve credit count'], 500);
        }
    }


}

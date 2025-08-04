<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DisableReasonController extends Controller
{
public function disableSeller(Request $request, $id)
{
    $request->validate([
        'user_status' => 'required|in:0,1',
    ]);

    $user = User::findOrFail($id);

    if ($user->user_role === 'seller') {
        $user->user_status = $request->user_status;

        // Correct column name: disable_reason
        if ($request->user_status == 0) {
            $user->disabled_reason = 'Disabled manually';
        } else {
            $user->disabled_reason = null;
        }

        $user->save();

        return redirect()->back()->with('success', 'Seller status updated successfully.');
    }

    return redirect()->back()->with('error', 'User is not a seller.');
}




}

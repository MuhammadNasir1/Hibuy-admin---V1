<?php

namespace App\Http\Controllers;

use App\Models\RiderModel;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    // public function riderIndex()
    // {
    //     return view('admin.rider');
    // }
    public function reiderCreate(Request $request) {
        try {
            $validRider = $request->validate([
            'rider_name' => 'required',
            'rider_email' => 'required|email|unique:riders,rider_email',
            'phone' => 'nullable|string|max:15',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
        ]);
        $rider = new RiderModel();
        $rider->rider_name = $validRider['rider_name'];
        $rider->rider_email = $validRider['rider_email'];
        $rider->phone = $validRider['phone'] ?? null;
        $rider->vehicle_type = $validRider['vehicle_type'] ?? null;
        $rider->vehicle_number = $validRider['vehicle_number'] ?? null;
        $rider->city = $validRider['city'] ?? 'faisalabad';
        $rider->save();

             return redirect()->back()->with('success','Rider created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create rider: ' . $e->getMessage()]);
        }
    }
    public function riderList() {
        try {
            $riders = RiderModel::all();
            return view('admin.rider', compact('riders'));
        } catch (\Exception $e){
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve riders: ' . $e->getMessage()]);
        }
    }
    // public function viewRider($id){
    //     try {
    //         $rider = RiderModel::findOrFail($id);
    //         return response()->json(['rider' => $rider]);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors(['error' => 'Failed to retrieve rider: ' . $e->getMessage()]);
    //     }
    // }
    public function deleteRider($id){
        try {
            $rider = RiderModel::findOrFail($id);
            $rider->delete();
            return redirect()->back()->with('success', 'Rider deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete rider: ' . $e->getMessage()]);
        }
    }
// Get single rider (for AJAX modal)
public function getRider($id)
{
    try {
        $rider = RiderModel::findOrFail($id);
        return response()->json(['rider' => $rider]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Rider not found'], 404);
    }
}

// Update rider (AJAX)
public function updateRider(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'rider_name' => 'required',
            'rider_email' => 'required|email|unique:riders,rider_email,' . $id,
            'phone' => 'nullable|string|max:15',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
        ]);

        $rider = RiderModel::findOrFail($id);
        $rider->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rider updated successfully',
            'rider' => $rider
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'error' => $e->validator->errors()->first()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Update failed: ' . $e->getMessage()
        ], 500);
    }
}


}

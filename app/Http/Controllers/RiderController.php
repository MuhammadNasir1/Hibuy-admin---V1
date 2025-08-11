<?php

namespace App\Http\Controllers;

use App\Models\RiderModel;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function riderCreate(Request $request)
    {
        try {

            $validate = $request->validate([
                'rider_email' => 'required|email|unique:riders,rider_email',
            ]);


            $rider = new RiderModel();
            $rider->rider_name = $request->rider_name;
            $rider->rider_email = $validate['rider_email'];
            $rider->phone = $request->phone;
            $rider->vehicle_type = $request->vehicle_type;
            $rider->vehicle_number = $request->vehicle_number;
            $rider->city = $request->city;
            $rider->save();

            return response()->json([
                'success' => true,
                'message' => 'Rider created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to create rider: ' . $e->getMessage()
            ], 500);
        }
    }
    public function riderList()
    {
        try {
            $riders = RiderModel::all();
            return view('admin.rider', compact('riders'));
        } catch (\Exception $e) {
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
    public function deleteRider($id)
    {
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

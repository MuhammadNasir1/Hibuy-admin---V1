<?php

namespace App\Http\Controllers;

use App\Models\RiderModel;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function riderIndex()
    {
        return view('admin.rider');
    }
    public function reiderCreate(Request $request) {
        try {
            $vaildRider = $request->validate([
            'rider_name' => 'required',
            'rider_email' => 'required|email|unique:riders,rider_email',
            'phone' => 'nullable|string|max:15',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
        ]);
        $rider = new RiderModel();
        $rider->rider_name = $vaildRider['rider_name'];
        $rider->rider_email = $vaildRider['rider_email'];
        $rider->phone = $vaildRider['phone'] ?? null;
        $rider->vehicle_type = $vaildRider['vehicle_type'] ?? null;
        $rider->vehicle_number = $vaildRider['vehicle_number'] ?? null;
        $rider->city = $vaildRider['city'] ?? 'faisalabad';
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
    public function viewRider($id){
        try {
            $rider = RiderModel::findOrFail($id);
            return response()->json(['rider' => $rider]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve rider: ' . $e->getMessage()]);
        }
    }
}

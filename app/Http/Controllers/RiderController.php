<?php

namespace App\Http\Controllers;

use App\Models\RiderModel;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function riderCreate(Request $request)
    {
        try {
            // Common validation rules
            $rules = [
                'rider_name'         => 'required|string|max:255',
                'phone'              => 'required|string|max:20',
                'vehicle_type'       => 'required|string|max:50',
                'vehicle_number'     => 'required|string|max:50',
                'city'               => 'required|string|max:100',
                'id_card_front'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'id_card_back'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'driving_licence_front' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'driving_licence_back'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'profile_picture'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];

            // For new record, email is required & unique
            if (empty($request->rider_id)) {
                $rules['rider_email'] = 'required|email|unique:riders,rider_email';
                $rules['id_card_front'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
                $rules['id_card_back'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
            } else {
                $rules['rider_email'] = 'required|email|unique:riders,rider_email,' . $request->rider_id . ',id';
            }

            $validate = $request->validate($rules);

            // If rider_id is given → update, else create new
            if (!empty($request->rider_id)) {
                $rider = RiderModel::findOrFail($request->rider_id);
            } else {
                $rider = new RiderModel();
            }

            // Assign values
            $rider->rider_name = $request->rider_name;
            $rider->rider_email = $validate['rider_email'];
            $rider->phone = $request->phone;
            $rider->vehicle_type = $request->vehicle_type;
            $rider->vehicle_number = $request->vehicle_number;
            $rider->city = $request->city;

            // File uploads (replace only if new file is provided)
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('riders/profile_pictures', 'public');
                $rider->profile_picture = 'storage/' . $path;
            }

            if ($request->hasFile('id_card_front')) {
                $path = $request->file('id_card_front')->store('riders/id_cards/front', 'public');
                $rider->id_card_front = 'storage/' . $path;
            }

            if ($request->hasFile('id_card_back')) {
                $path = $request->file('id_card_back')->store('riders/id_cards/back', 'public');
                $rider->id_card_back = 'storage/' . $path;
            }

            if ($request->hasFile('driving_licence_front')) {
                $path = $request->file('driving_licence_front')->store('riders/licences/front', 'public');
                $rider->driving_license_front = 'storage/' . $path;
            }

            if ($request->hasFile('driving_licence_back')) {
                $path = $request->file('driving_licence_back')->store('riders/licences/back', 'public');
                $rider->driving_license_back = 'storage/' . $path;
            }

            $rider->save();

            return response()->json([
                'success' => true,
                'message' => !empty($request->rider_id) ? 'Rider updated successfully' : 'Rider created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to save rider: ' . $e->getMessage()
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

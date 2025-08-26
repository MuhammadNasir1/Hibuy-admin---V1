<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function insert(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'type' => 'required',
            ]);

            $notification = new Notification();
            $notification->sent_by = session('user_details')['user_id'];
            $notification->received_by = $request->type;
            $notification->title = $request->title;
            $notification->description = $request->description;
            $notification->type = $request->type;

            $notification->save();
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show()
    {
        if (session('user_details')['user_role'] !== 'admin' && session('user_details')['user_role'] !== 'staff' && session('user_details')['user_role'] !== 'manager') {
            return redirect()->route('allNotifications');
        }

        $notifications = Notification::all();
        return view('pages.Notifications', compact('notifications'));
    }

    public function delete(string $id)
    {
        try {
            $notification = Notification::where('notification_id', $id)->first();
            if ($notification) {
                $notification->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Notification deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getNotifications()
    {



        try {
            $userRole = session('user_details')['user_role'];
            $notifications = Notification::where('type', $userRole)->get();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

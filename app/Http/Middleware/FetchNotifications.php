<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FetchNotifications
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $userRole = session('user_details')['user_role'] ?? null;
            if ($userRole) {
                $notifications = Notification::where('type', $userRole)
                    ->where('notification_status', 'unread')
                    ->latest() // Orders by created_at descending by default
                    ->take(5)   // Limits to 5 results
                    ->get();

                view()->share('notifications', $notifications);
                $request->session()->put('notifications', $notifications);
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch notifications: ' . $e->getMessage());
        }


        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Seller;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSellerKyc
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session('user_details')['user_id'];
        $user_role = session('user_details')['user_role'];
        if ($user_role  == 'seller' || $user_role == 'freelancer') {
            $seller = Seller::where('user_id', $user)->first();
            if ($seller->status !== 'approved') {
                return redirect()->route("kycView");
            }
        }

        return $next($request);
    }
}

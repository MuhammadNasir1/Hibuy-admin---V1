<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function GetOrders()
    {
        try {
            // Fetch all orders using Eloquent
            $orders = Order::all();
// return $orders;
            // Return view with compact array
            return view('pages.Orders', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Seller;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $sellerId = Auth::id();

        $totalProducts = Product::where('user_id', $sellerId)->count();
        $totalOrders = Order::whereHas('orderDetails', function($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        })->count();

        $totalRevenue = Order::whereHas('orderDetails', function($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        })->sum('total_amount');

        return view('client.seller.dashboard', compact('totalProducts', 'totalOrders', 'totalRevenue'));
    }
}

<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    // Daftar order untuk seller
    public function index()
    {
        $sellerId = Auth::id();
        $orders = Order::whereHas('orderDetails', function($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        })->paginate(20);

        return view('client.seller.orders.index', compact('orders'));
    }

    // Detail order
    public function show($id)
    {
        $sellerId = Auth::id();
        $order = Order::where('id', $id)
            ->whereHas('orderDetails', function($q) use ($sellerId) {
                $q->where('user_id', $sellerId);
            })->firstOrFail();

        return view('client.seller.orders.show', compact('order'));
    }

    // Update status order (misal: diproses, dikirim, selesai)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,canceled',
        ]);

        $sellerId = Auth::id();
        $order = Order::where('id', $id)
            ->whereHas('orderDetails', function($q) use ($sellerId) {
                $q->where('user_id', $sellerId);
            })->firstOrFail();

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('seller.orders.show', $id)
            ->with('success', 'Status order berhasil diperbarui');
    }
}

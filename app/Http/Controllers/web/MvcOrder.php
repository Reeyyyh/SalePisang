<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MvcOrder extends Controller
{
    public function checkoutForm()
    {
        $user = Auth::user();
        $cartItems = CartProduct::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with([
                'message' => 'Keranjang kosong!',
                'status'  => 'info'
            ]);
        }

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        // 🔽 ambil channel pembayaran Tripay
        $apiKey   = config('services.tripay.api_key');
        $baseUrl  = config('services.tripay.base_url');

        $response = Http::withToken($apiKey)
            ->get($baseUrl . 'merchant/payment-channel');

        // dd($response->json());

        $channels = $response->json('data') ?? [];

        return view('cart.checkout', [
            'cartItems' => $cartItems,
            'total'     => $total,
            'channels'  => $channels,
        ]);
    }


    public function cancelOrder($uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->where('orders_status', 'pending')
            ->firstOrFail();

        $order->update([
            'orders_status' => 'canceled'
        ]);

        PaymentStatus::where('order_id', $order->id)->update([
            'status' => 'canceled'
        ]);

        return redirect()->route('profile.history')->with([
            'message' => 'Pesanan berhasil dibatalkan.',
            'status'  => 'success'
        ]);
    }

    public function waitingPayment($uuid)
    {
        $order = Order::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();

        return view('payment-view.waiting-payment', compact('order'));
    }


    public function processCheckout(Request $request)
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */
        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        if (!$defaultAddress) {
            return redirect()->route('profile.address')
                ->with('message', 'Silakan lengkapi alamat pengiriman terlebih dahulu sebelum melanjutkan pembayaran.')
                ->with('status', 'warning');
        }

        // Validasi metode pembayaran
        $request->validate([
            'method' => 'required|string'
        ]);

        // Ambil keranjang
        $cartItems = CartProduct::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with([
                'message' => 'Keranjang kosong!',
                'status'  => 'info'
            ]);
        }

        // Cek stok
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()->with([
                    'message' => "Stok produk {$item->product->product_name} tidak cukup.",
                    'status'  => 'info'
                ]);
            }
        }

        // Hitung total & generate invoice
        $totalAmount = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $invoice     = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // Buat order sementara (status pending)
        $shippingAddress = implode("\n", array_filter([
            $defaultAddress->fullname,
            $defaultAddress->company !== '-' ? $defaultAddress->company : null,
            $defaultAddress->address1,
            $defaultAddress->address2 !== '-' ? $defaultAddress->address2 : null,
            $defaultAddress->city . ', ' . $defaultAddress->postal,
            $defaultAddress->country,
            'Telp: ' . $defaultAddress->phone
        ]));

        $order = Order::create([
            'uuid'             => Str::uuid(),
            'invoice_number'   => $invoice,
            'user_id'          => $user->id,
            'total_amount'     => $totalAmount,
            'shipping_address' => $shippingAddress,
            'orders_status'    => 'pending',
            'payment_expired_at' => now()->addHours(6),
        ]);

        PaymentStatus::create([
            'order_id' => $order->id,
            'status'   => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderDetail::create([
                'order_id'  => $order->id,
                'product_id' => $item->product_id,
                'quantity'  => $item->quantity,
                'price'     => $item->product->price,
                'subtotal'  => $item->quantity * $item->product->price,
            ]);
        }

        CartProduct::where('user_id', $user->id)->delete();

        // Tripay config
        $apiKey       = config('services.tripay.api_key');
        $privateKey   = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');

        $merchantRef  = $invoice;
        $amount       = $totalAmount;

        // Signature
        $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

        // Payload untuk Tripay
        $payload = [
            'method'         => $request->input('method'),
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '-',
            'order_items'    => $cartItems->map(fn($item) => [
                'sku'      => $item->product->id,
                'name'     => $item->product->product_name,
                'price'    => $item->product->price,
                'quantity' => $item->quantity,
            ])->toArray(),
            'callback_url'   => route('tripay.callback'),
            'return_url'     => route('profile.history'),
            'expired_time'   => time() + (6 * 60 * 60),
            'signature'      => $signature,
        ];

        // Request ke Tripay
        $response = Http::withToken($apiKey)
            ->post(config('services.tripay.base_url') . 'transaction/create', $payload)
            ->json();

        if ($response['success'] ?? false) {
            $order->update([
                'payment_method'     => $request->input('method'),
                'tripay_reference'   => $response['data']['reference'],
                'payment_url'        => $response['data']['checkout_url'],
                'payment_expired_at' => Carbon::createFromTimestamp($response['data']['expired_time']),
            ]);

            return redirect()->route('order.waiting', $order->uuid);
        }

        return redirect()->route('cart')->with([
            'message' => 'Gagal membuat transaksi di Tripay: ' . ($response['message'] ?? 'Unknown error'),
            'status'  => 'error',
        ]);
    }
}

<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Midtrans\Config;
use Midtrans\Snap;

class MvcMidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(Order $order)
    {
        $order->load(['user', 'orderDetails.product']);

        $defaultAddress = $order->user->addresses()->where('is_default', true)->first();

        if (!$defaultAddress) {
            return redirect()->route('profile.address')
                ->with('message', 'Silakan lengkapi alamat pengiriman terlebih dahulu sebelum melanjutkan pembayaran.')
                ->with('status', 'warning');
        }

        $recipient = $defaultAddress->fullname ?? $order->user->name;
        $phone = $defaultAddress->phone ?? $order->user->phone_number ?? '';

        // Hitung durasi dari sekarang sampai payment_expired_at
        $duration = now()->diffInMinutes($order->payment_expired_at, false);
        $duration = max(1, $duration);

        $transaction = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id . '-' . time(),
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $recipient,
                'email' => $order->user->email ?? 'noemail@example.com',
                'phone' => $phone,
                'billing_address' => [
                    'first_name' => $recipient,
                    'phone' => $phone,
                    'address' => $defaultAddress->address1 . ' ' . $defaultAddress->address2,
                    'city' => $defaultAddress->city,
                    'postal_code' => $defaultAddress->postal,
                    'country_code' => $defaultAddress->country,
                ],
                'shipping_address' => [
                    'first_name' => $recipient,
                    'phone' => $phone,
                    'address' => $defaultAddress->address1 . ' ' . $defaultAddress->address2,
                    'city' => $defaultAddress->city,
                    'postal_code' => $defaultAddress->postal,
                    'country_code' => $defaultAddress->country,
                ],
            ],
            'item_details' => $order->orderDetails->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'name' => optional($item->product)->product_name ?? 'Unknown',
                ];
            })->toArray(),

            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => $duration,
            ],
        ];

        return Snap::getSnapToken($transaction);
    }

    public function notificationHandler(Request $request)
    {
        // 🔹 1. Validasi Signature Key
        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($expectedSignature !== $request->signature_key) {
            Log::error("Invalid signature key for order: " . $request->order_id);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 🔹 2. Ambil order_id dari notifikasi
        $orderIdParts = explode('-', $request->order_id);
        $orderId = $orderIdParts[1] ?? null;

        $order = Order::where('id', $orderId)->first();
        if (!$order) {
            Log::error("Order not found: " . $request->order_id);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 🔹 3. Ambil atau buat metode pembayaran
        $paymentMethod = PaymentMethod::firstOrCreate(
            ['code' => $request->payment_type],
            [
                'name' => ucfirst(str_replace('_', ' ', $request->payment_type)),
                'provider' => 'Midtrans',
            ]
        );

        // 🔹 4. Perbarui atau buat payment status
        $order->paymentStatus()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method_id' => $paymentMethod->id,
                'status' => $request->transaction_status,
                'midtrans_transaction_id' => $request->transaction_id,
                'transaction_time' => $request->transaction_time ?? now(),
                'fraud_status' => $request->fraud_status ?? 'unknown',
                'reference_id' => $request->reference_id ?? null,
            ]
        );

        // 🔹 5. Update status pesanan berdasarkan status transaksi
        if ($request->transaction_status === 'settlement') {
            $order->update(['orders_status' => 'paid']); //Ubah orders_status jadi status untuk menyesuaikan database
            // Hapus keranjang hanya jika pembayaran sukses
            CartProduct::where('user_id', $order->user_id)->delete();

            // 🔹 6. Kurangi stok produk jika pembayaran sukses
            foreach ($order->orderDetails as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();
                }
            }
        } elseif ($request->transaction_status === 'pending') {
            $order->update(['orders_status' => 'pending']);
        } elseif (in_array($request->transaction_status, ['expire', 'cancel', 'deny'])) {
            $order->update(['orders_status' => 'failed']);
        }

        // 🔹 7. Logging sukses
        Log::info("Midtrans notification processed for order: " . $order->id);

        return response()->json(['message' => 'Transaction status updated']);
    }

    public function success()
    {
        return view('payment-view.success');
    }

    public function failed()
    {
        return view('payment-view.failed');
    }
}

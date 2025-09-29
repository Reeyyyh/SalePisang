<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MvcTripayController extends Controller
{
    // Handle callback dari Tripay
    public function handleCallback(Request $request)
{
    $json = $request->getContent();
    $data = json_decode($json, true);

    if (!$data) {
        return response()->json(['success' => false, 'message' => 'Invalid callback'], 400);
    }

    $callbackSignature = $request->header('X-Callback-Signature');
    $privateKey = config('services.tripay.private_key');
    $signature = hash_hmac('sha256', $json, $privateKey);

    if ($callbackSignature !== $signature) {
        return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
    }

    // 🚀 Jawab dulu ke Tripay agar tidak timeout
    response()->json(['success' => true])->send();
    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request(); // kalau pakai nginx + php-fpm
    }

    // 🔽 Baru proses update order
    try {
        DB::beginTransaction();

        $order = \App\Models\Order::where('invoice_number', $data['merchant_ref'])->first();

        if ($order) {
            $statusMap = [
                'UNPAID'   => 'pending',
                'PAID'     => 'paid',
                'EXPIRED'  => 'expired',
                'FAILED'   => 'canceled',
                'REFUND'   => 'canceled',
            ];

            $newStatus = $statusMap[$data['status']] ?? 'pending';

            $order->update([
                'orders_status' => $newStatus,
                'paid_at'       => $data['status'] === 'PAID' ? now() : null,
            ]);

            if ($newStatus === 'paid') {
                foreach ($order->orderDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        if ($product->stock >= $detail->quantity) {
                            $product->decrement('stock', $detail->quantity);
                        } else {
                            Log::warning("Stok produk tidak mencukupi untuk Product ID {$detail->product_id}, dibutuhkan {$detail->quantity}, tersedia {$product->stock}");
                        }
                    }
                }
            }

            $order->paymentStatus()->update([
                'status' => $newStatus,
            ]);
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Tripay Callback Error: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'data' => $data,
        ]);
    }

    return; // sudah dijawab ke Tripay di awal
}

}

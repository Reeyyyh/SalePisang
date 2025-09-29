<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Order;
use App\Models\OrderDetail;

class SalesChart extends LineChartWidget
{
    protected static ?string $heading = 'Sales This Year';
    protected static ?string $description = '';

    protected function getData(): array
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin'; // ✅ sebelumnya super-admin

        if ($isAdmin) {
            // Admin (dulunya super-admin) lihat semua
            $sales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                ->whereYear('created_at', date('Y'))
                ->where('orders_status', 'paid') // ✅ hanya order yang sudah dibayar
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();
        } else {
            // Seller (dulunya admin) hanya lihat penjualan produknya sendiri
            $sales = OrderDetail::selectRaw('MONTH(order_details.created_at) as month, SUM(order_details.price * order_details.quantity) as total')
                ->whereYear('order_details.created_at', date('Y'))
                ->join('products', 'order_details.product_id', '=', 'products.id')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('products.user_id', $user->id)
                ->where('orders.orders_status', 'paid')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();
        }

        // Label dan data untuk 12 bulan
        $labels = [];
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = date('F', mktime(0, 0, 0, $m, 1));
            $data[] = $sales[$m] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                ],
            ],
        ];
    }
}

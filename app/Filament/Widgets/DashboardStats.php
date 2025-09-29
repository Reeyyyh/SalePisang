<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin'; // ganti super-admin → admin

        // Ambil data 7 hari terakhir
        $userChartData = $isAdmin ? $this->getDailyCount(User::class) : [];
        $orderChartData = $this->getDailyCount(Order::class);
        $productChartData = $this->getDailyCount(Product::class);
        $revenueChartData = $this->getDailySum(Order::class, 'total_amount');

        // Total sekarang
        $currentUsers = $isAdmin ? array_sum($userChartData) : 0;
        $currentOrders = array_sum($orderChartData);
        $currentProducts = array_sum($productChartData);
        $currentRevenue = array_sum($revenueChartData);

        // Total minggu lalu (untuk comparison)
        $lastWeekUsers = $isAdmin ? $this->getPreviousWeekTotal(User::class) : 0;
        $lastWeekOrders = $this->getPreviousWeekTotal(Order::class);
        $lastWeekProducts = $this->getPreviousWeekTotal(Product::class);
        $lastWeekRevenue = $this->getPreviousWeekTotal(Order::class, 'total_amount');

        $stats = [];

        if ($isAdmin) {
            $stats[] = Stat::make('Total Users', $currentUsers)
                ->description($this->formatChange($this->getPercentageChange($currentUsers, $lastWeekUsers)))
                ->descriptionIcon($this->getTrendIcon($this->getPercentageChange($currentUsers, $lastWeekUsers)))
                ->color('primary')
                ->chart($userChartData);
        }

        $stats[] = Stat::make('Total Orders', $currentOrders)
            ->description($this->formatChange($this->getPercentageChange($currentOrders, $lastWeekOrders)))
            ->descriptionIcon($this->getTrendIcon($this->getPercentageChange($currentOrders, $lastWeekOrders)))
            ->color('secondary')
            ->chart($orderChartData);

        $stats[] = Stat::make('Total Products', $currentProducts)
            ->description($this->formatChange($this->getPercentageChange($currentProducts, $lastWeekProducts)))
            ->descriptionIcon($this->getTrendIcon($this->getPercentageChange($currentProducts, $lastWeekProducts)))
            ->color('success')
            ->chart($productChartData);

        $stats[] = Stat::make('Total Revenue', 'Rp' . number_format($currentRevenue, 2))
            ->description($this->formatChange($this->getPercentageChange($currentRevenue, $lastWeekRevenue)))
            ->descriptionIcon($this->getTrendIcon($this->getPercentageChange($currentRevenue, $lastWeekRevenue)))
            ->color('danger')
            ->chart($revenueChartData);

        return $stats;
    }

    private function getDailyCount($model): array
    {
        return $this->getDailyStats($model, 'count');
    }

    private function getDailySum($model, $column): array
    {
        return $this->getDailyStats($model, 'sum', $column);
    }

    private function getDailyStats($model, $type = 'count', $column = '*'): array
    {
        $data = [];
        $user = Auth::user();
        $isAdmin = $user->role === 'admin'; // super-admin → admin

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            if ($model === Order::class) {
                $query = Order::whereDate('created_at', $date);

                // Hanya order yang sudah settlement (dibayar lunas)
                $query->whereHas('paymentStatus', function ($q) {
                    $q->where('status', 'settlement');
                });

                // Kalau seller → hanya order dari produknya sendiri
                if (!$isAdmin) {
                    $query->whereHas('orderDetails.product', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                }

                $value = $type === 'sum' ? $query->sum($column) : $query->count();
            } else {
                $query = $model::whereDate('created_at', $date);

                if (!$isAdmin && $model === Product::class) {
                    $query->where('user_id', $user->id);
                }

                $value = $type === 'sum' ? $query->sum($column) : $query->count();
            }

            $data[] = $value;
        }

        return $data;
    }

    private function getPreviousWeekTotal($model, $column = '*'): float|int
    {
        $start = now()->subWeek()->startOfWeek();
        $end = now()->subWeek()->endOfWeek();

        $user = Auth::user();
        $isAdmin = $user->role === 'admin'; // super-admin → admin

        // Hitung REVENUE kalau seller
        if ($model === Order::class && !$isAdmin && $column !== '*') {
            return \App\Models\OrderDetail::whereBetween('created_at', [$start, $end])
                ->whereHas('product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereHas('order', function ($query) {
                    $query->where('orders_status', 'paid');
                })
                ->select(DB::raw('SUM(price * quantity) as total'))
                ->value('total') ?? 0;
        }

        // Hitung jumlah ORDER untuk seller
        if ($model === Order::class && !$isAdmin) {
            return Order::whereBetween('created_at', [$start, $end])
                ->whereHas('orderDetails.product', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();
        }

        // Untuk admin → bisa lihat semua
        $query = $model::whereBetween('created_at', [$start, $end]);

        if (!$isAdmin && $model === Product::class) {
            $query->where('user_id', $user->id);
        }

        return $column === '*' ? $query->count() : $query->sum($column);
    }

    private function getPercentageChange($current, $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    private function getTrendIcon($change): string
    {
        if ($change > 0) {
            return 'heroicon-m-arrow-trending-up';
        } elseif ($change < 0) {
            return 'heroicon-m-arrow-trending-down';
        } else {
            return 'heroicon-m-minus';
        }
    }

    private function formatChange($change): string
    {
        return ($change > 0 ? 'Increased' : ($change < 0 ? 'Decreased' : 'No change')) . ' by ' . abs(round($change, 1)) . '%';
    }
}

<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\BarChartWidget;
use Livewire\Component;

class TotalCustomers extends BarChartWidget
{
    protected static ?string $heading = 'Customers Per Month';

    protected function getData(): array
    {
        // Ambil data pelanggan per bulan dalam 1 tahun terakhir
        $customers = DB::table('users')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Siapkan array bulan
        $months = collect(range(0, 11))->map(function ($i) {
            return now()->subMonths(11 - $i)->format('Y-m');
        });

        $labels = [];
        $data = [];

        foreach ($months as $month) {
            $labels[] = Carbon::createFromFormat('Y-m', $month)->format('F Y');
            $data[] = $customers->firstWhere('month', $month)->count ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => $data,
                    'backgroundColor' => '#60A5FA',
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    protected function getType(): string
    {
        return 'bar'; // atau 'line' jika kamu ingin grafik garis
    }
}

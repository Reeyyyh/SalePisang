<?php

namespace App\Filament\Widgets;

use Filament\Widgets\PieChartWidget;
use Illuminate\Support\Facades\DB;

class TopCategoriesChart extends PieChartWidget
{
    protected static ?string $heading = 'Top Selling Categories';

    protected function getData(): array
    {
        $salesPerCategory = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.category_name as category_name', DB::raw('SUM(order_details.quantity) as total_quantity'))
            ->groupBy('categories.category_name')
            ->orderByDesc('total_quantity')
            ->pluck('total_quantity', 'category_name')
            ->toArray();

        return [
            'labels' => array_keys($salesPerCategory),
            'datasets' => [
                [
                    'label' => 'Quantity Sold',
                    'data' => array_values($salesPerCategory),
                    'backgroundColor' => [
                        '#F87171', // merah
                        '#60A5FA', // biru
                        '#34D399', // hijau
                        '#FBBF24', // kuning
                        '#A78BFA', // ungu
                        '#F472B6', // pink
                        '#38BDF8', // cyan
                    ],
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                    ],
                ],
            ],
            'type' => 'pie', // pastikan ini adalah 'pie'
        ];
    }
}

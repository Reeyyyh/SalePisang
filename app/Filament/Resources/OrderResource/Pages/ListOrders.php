<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua Pesanan' => Tab::make(),

            'Menunggu Pembayaran' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('paymentStatus', function ($q) {
                        $q->where('status', 'pending');
                    })
                ),

            'Sudah Dibayar' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('paymentStatus', function ($q) {
                        $q->whereIn('status', ['settlement', 'capture']);
                    })
                ),

            'Dibatalkan / Expired' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->whereHas('paymentStatus', function ($q) {
                        $q->whereIn('status', ['expired', 'canceled', 'deny', 'failure']);
                    })
                ),
        ];
    }
}

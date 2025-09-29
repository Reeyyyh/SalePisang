<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Illuminate\Database\Eloquent\Model;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Pengguna')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')->label('Nama User'),
                        TextEntry::make('user.email')->label('Email'),
                        TextEntry::make('user.phone_number')->label('Nomor Telepon'),
                    ]),

                Section::make('Alamat Pengiriman')
                    ->columns(1)
                    ->schema([
                        TextEntry::make('user.defaultAddress')
                            ->label('Alamat Pengiriman (Default)')
                            ->formatStateUsing(function ($record) {
                                $address = optional($record->user->defaultAddress);
                                if (!$address) {
                                    return 'Alamat tidak tersedia';
                                }

                                return "{$address->address1}, {$address->address2}, {$address->city}, {$address->postal}, {$address->country}";
                            }),
                    ]),

                Section::make('Detail Pesanan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('orders_status')
                            ->label('Status Order')
                            ->formatStateUsing(fn($state) => ucfirst($state)),

                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('IDR'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y - H:i'),
                    ]),

                Section::make('Produk Dipesan')
                    ->schema([
                        RepeatableEntry::make('orderDetails')
                            ->label('Produk')
                            ->schema([
                                TextEntry::make('product.product_name')->label('Nama Produk'),
                                TextEntry::make('quantity')->label('Jumlah'),
                                TextEntry::make('price')->label('Harga Satuan')->money('IDR'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR'),
                            ])
                            ->columnSpanFull()
                    ]),
            ]);
    }

    protected function resolveRecord(int|string $key): Model
    {
        return parent::resolveRecord($key)->loadMissing([
            'user',
            'user.defaultAddress',
            'orderDetails.product',
        ]);
    }
}

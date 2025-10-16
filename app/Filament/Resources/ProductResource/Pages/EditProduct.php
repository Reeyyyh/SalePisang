<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol hapus gambar
            Action::make('deleteImage')
                ->label('Hapus Gambar')
                ->action(function () {
                    if ($this->record->image) {
                        Storage::disk('public')->delete($this->record->image);
                        $this->record->update(['image' => null]);
                    }

                    Notification::make()
                        ->success()
                        ->title('Gambar produk berhasil dihapus!')
                        ->send();
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->record && $this->record->image),

            // Tombol hapus produk
            Actions\DeleteAction::make()
                ->label('Hapus Produk')
                ->before(function ($record) {
                    // Simpan nama produk sebelum dihapus (record akan null setelah delete)
                    session()->put('deleted_product_name', $record->product_name);
                })
                ->successNotification(function () {
                    // Ambil nama produk dari session agar tetap bisa diakses setelah delete
                    $productName = session()->pull('deleted_product_name', 'Produk');

                    return Notification::make()
                        ->success()
                        ->title("{$productName} berhasil dihapus!");
                }),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Produk berhasil diperbarui!');
        // ->body('Data produk telah disimpan dengan sukses.');
    }
}

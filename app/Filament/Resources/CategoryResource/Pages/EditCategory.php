<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol hapus gambar hanya muncul kalau ada gambar
            Action::make('deleteImage')
                ->label('Hapus Gambar')
                ->action(function () {
                    if ($this->record->image) {
                        Storage::disk('public')->delete($this->record->image);
                        $this->record->update(['image' => null]);
                    }
                })
                ->requiresConfirmation()
                ->visible(fn() => $this->record && $this->record->image),

            // Tombol hapus kategori
            Actions\DeleteAction::make()->label('Hapus Category'),
        ];
    }
}

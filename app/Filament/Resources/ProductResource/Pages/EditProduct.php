<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [


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

            Actions\DeleteAction::make()->label('Hapus Product'),
        ];
    }
}

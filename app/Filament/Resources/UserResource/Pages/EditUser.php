<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn() => $this->record->role === 'admin')
                ->before(function ($record) {
                    if ($record->role === 'admin') {
                        abort(403, 'Tidak diizinkan menghapus Admin.');
                    }
                }),
        ];
    }
}

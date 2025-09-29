<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(function () {
                $auth = auth()->user();

                return [
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    Select::make('role')
                        ->label('Role')
                        ->options([
                            'seller' => 'Seller',
                            'user' => 'User',
                        ])
                        ->required()
                        ->default('user')
                        ->visible(fn() => $auth->role === 'admin')
                        ->disabled(fn($record) => $record && $record->id === $auth->id),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->maxLength(255)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context) => $context === 'create'),

                    DatePicker::make('tanggal_lahir')
                        ->label('Tanggal Lahir'),

                    TextInput::make('phone_number')
                        ->label('Nomor Telepon')
                        ->tel(),

                    TextInput::make('fullname')
                        ->label('Nama Lengkap (Profil)')
                        ->maxLength(255),

                    TextInput::make('company')
                        ->label('Perusahaan')
                        ->maxLength(255)
                        ->nullable()
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    TextInput::make('address1')
                        ->label('Alamat 1')
                        ->maxLength(255)
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    TextInput::make('address2')
                        ->label('Alamat 2')
                        ->maxLength(255)
                        ->nullable()
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    TextInput::make('country')
                        ->label('Negara')
                        ->maxLength(100)
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    TextInput::make('city')
                        ->label('Kota')
                        ->maxLength(100)
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    TextInput::make('postal')
                        ->label('Kode Pos')
                        ->maxLength(20)
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    TextInput::make('phone')
                        ->label('No. HP Alternatif')
                        ->tel()
                        ->visible(fn($record) => !($auth->role === 'admin' && $record && $record->id === $auth->id)),

                    DatePicker::make('email_verified_at')
                        ->label('Email Diverifikasi Pada'),

                    DatePicker::make('verification_expires_at')
                        ->label('Verifikasi Expired Pada'),
                ];
            })
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone_number')
                    ->label('Telepon')
                    ->searchable(),

                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date(),

                TextColumn::make('email_verified_at')
                    ->label('Email Diverifikasi')
                    ->dateTime('d M Y - H:i'),

                TextColumn::make('role'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y - H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn($record) => $record->role === 'admin'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        $superAdminRecords = $records->filter(fn($record) => $record->role === 'admin');

                        if ($superAdminRecords->isNotEmpty()) {
                            Notification::make()
                                ->title('Aksi tidak diizinkan')
                                ->body('Tidak boleh menghapus user dengan role admin.')
                                ->danger()
                                ->send();

                            abort(403, 'Tidak boleh menghapus admin.');
                        }
                    }),
            ])
            ->searchable();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'admin';
    }
}

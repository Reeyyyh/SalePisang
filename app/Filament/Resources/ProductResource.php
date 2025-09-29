<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('uploaded_by_name')
                    ->label('Nama Admin Pengunggah')
                    ->content(fn($record) => $record?->user?->name)
                    ->visible(fn() => auth()->user()?->role === 'admin'),

                Placeholder::make('uploaded_by_email')
                    ->label('Email Admin Pengunggah')
                    ->content(fn($record) => $record?->user?->email)
                    ->visible(fn() => auth()->user()?->role === 'admin'),

                ViewField::make('current_image')
                    ->view('filament.components.product-current-image')
                    ->columnSpan('full'),

                FileUpload::make('image')
                    ->label('Gambar Produk')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->nullable()
                    ->preserveFilenames()
                    ->deleteUploadedFileUsing(fn($file) => Storage::disk('public')->delete($file))
                    ->visible(fn($record) => !$record || !$record->image),

                TextInput::make('product_name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),

                TextInput::make('sku')
                    ->label('SKU')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit', 'view')
                    ->nullable(),

                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TextInput::make('stock')
                    ->label('Stok')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TextInput::make('weight')
                    ->label('Berat (Kg)')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->suffix('Kg')
                    ->nullable(),

                Radio::make('status')
                    ->label('Status Stok')
                    ->options([
                        'available' => 'Tersedia',
                        'out_of_stock' => 'Habis',
                    ])
                    ->inline() // biar sejajar horizontal
                    ->default('available')
                    ->required(),


                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->maxLength(65535),

                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'category_name')
                    ->required(),

                Checkbox::make('is_featured')
                    ->label('Tampilkan di Halaman Utama')
                    ->default(false)
                    ->visible(fn() => auth()->user()?->role === 'admin'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter(),

                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->width(50)
                    ->height(50),

                TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()?->role === 'seller'),

                TextColumn::make('user.name')
                    ->label('Diunggah Oleh')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()?->role === 'admin'),

                TextColumn::make('user.email')
                    ->label('Email pengunggah')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()?->role === 'admin'),

                TextColumn::make('category.category_name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('idr', true)
                    ->alignEnd()
                    ->sortable()
                    ->visible(fn() => auth()->user()?->role === 'seller'),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->visible(fn() => auth()->user()?->role === 'seller'),

                TextColumn::make('weight')
                    ->label('Berat')
                    ->suffix(' Kg')
                    ->sortable()
                    ->visible(fn() => auth()->user()?->role === 'seller'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'available' => 'success',
                        'out_of_stock' => 'danger',
                    ])
                    ->sortable()
                    ->visible(fn() => auth()->user()?->role === 'seller'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y - H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->searchable();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->role === 'seller') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

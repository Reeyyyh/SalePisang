<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

use Filament\Forms\Components\TextInput\Mask;

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
                    ->maxLength(120),

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
                    ->minValue(1000)
                    ->maxValue(10000000)
                    ->prefix('Rp'),

                TextInput::make('stock')
                    ->label('Jumlah Stok')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(9999),

                TextInput::make('weight')
                    ->label('Berat (Kg)')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->suffix('Kg')
                    ->nullable(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->maxLength(65535),

                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'category_name')
                    ->required(),

                Fieldset::make('Status Produk')
                    ->schema([
                        Radio::make('status')
                            ->label('Stok')
                            ->options([
                                'available' => 'Tersedia',
                                'out_of_stock' => 'Habis',
                            ])
                            ->default('available')
                            ->required()
                            ->columnSpan(1),

                        Grid::make(1)
                            ->schema([
                                Checkbox::make('is_verified')
                                    ->label('Tampilkan Produk')
                                    ->default(true),
                                // ->visible(fn() => auth()->user()?->role === 'admin'),

                                Checkbox::make('is_featured')
                                    ->label('Produk Pilihan')
                                    ->default(false)
                                    ->visible(fn() => auth()->user()?->role === 'admin'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(2),

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
                Tables\Actions\DeleteAction::make()
                    ->label('Delete')
                    ->before(function ($record) {
                        // simpan nama produk ke session sebelum dihapus
                        session()->put('deleted_product_name', $record->product_name);
                    })
                    ->successNotification(function () {
                        // ambil nama dari session setelah delete selesai
                        $productName = session()->pull('deleted_product_name', 'Produk');

                        return Notification::make()
                            ->success()
                            ->title("{$productName} berhasil dihapus!");
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            $names = $records->pluck('product_name')->toArray();
                            session()->put('deleted_product_names', $names);
                        })
                        ->successNotification(function () {
                            $names = session()->pull('deleted_product_names', []);
                            $count = count($names);
                            $title = $count > 1 ? "{$count} produk berhasil dihapus!" : (count($names) === 1 ? "{$names[0]} berhasil dihapus!" : "Produk berhasil dihapus!");
                            return Notification::make()->success()->title($title);
                        }),
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

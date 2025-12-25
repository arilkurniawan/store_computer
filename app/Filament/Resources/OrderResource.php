<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Data Pembeli')
            ->schema([
                TextInput::make('order_code')
                    ->label('Kode Order')
                    ->disabled(),

                TextInput::make('name')
                    ->label('Nama')
                    ->disabled(),

                TextInput::make('phone')
                    ->label('No. HP')
                    ->disabled(),

                Textarea::make('address')
                    ->label('Alamat')
                    ->disabled(),

                TextInput::make('city')
                    ->label('Kota')
                    ->disabled(),

                TextInput::make('post_code')
                    ->label('Kode Pos')
                    ->disabled(),
            ])
            ->columns(2),

                Section::make('Pembayaran')
            ->schema([
                TextInput::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->disabled(),

                TextInput::make('promo.code')
                    ->label('Kode Promo')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) => $record?->promo?->code ?? '-'),

                TextInput::make('discount_amount')
                    ->label('Diskon')
                    ->numeric()
                    ->disabled(),

                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->required(),
            ])
                    ->columns(2),
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('order_code')
                ->label('Kode')
                ->searchable(),

            TextColumn::make('name')
                ->label('Pembeli')
                ->searchable(),

            TextColumn::make('subtotal_produk')
                ->label('Subtotal Produk')
                ->getStateUsing(fn ($record) =>
                    $record->total_price + $record->discount_amount)
                ->money('IDR'),

            TextColumn::make('discount_amount')
                ->label('Diskon')
                ->money('IDR')
                ->color('success'),

            TextColumn::make('total_price')
                ->label('Total Bayar')
                ->money('IDR')
                ->weight('bold'),

            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'paid',
                    'danger' => 'failed',
                ]),

            TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime(),
            ])
            ->filters([
                //
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

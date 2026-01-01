<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Item Pesanan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product.image')
                    ->label('Gambar')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('product_name')
                    ->label('Produk')
                    ->searchable(),

                Tables\Columns\TextColumn::make('product_price')
                    ->label('Harga Satuan')
                    ->money('IDR', locale: 'id'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),
            ])
            ->paginated(false);
    }
}

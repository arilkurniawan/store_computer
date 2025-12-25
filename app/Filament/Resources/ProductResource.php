<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
        TextInput::make('name')
            ->label('Nama Produk')
            ->required()
            ->maxLength(255),

        TextInput::make('price')
            ->label('Harga')
            ->numeric()
            ->required()
            ->prefix('IDR'),

        Select::make('category_id')
            ->label('Kategori')
            ->relationship('category', 'name')
            ->required(),

        TextInput::make('stock')
            ->label('Stok')
            ->numeric()
            ->required(),

        Textarea::make('description')
            ->label('Deskripsi')
            ->required()
            ->columnSpanFull(),

        FileUpload::make('image')
            ->label('Gambar Produk')
            ->image()
            ->directory('products')
            ->imagePreviewHeight('150')
            ->required(),
            
        Toggle::make('is_recommended')
            ->label('Produk Rekomendasi')
            ->default(false),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('image')
                ->label('Gambar'),

            TextColumn::make('name')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('category.name')
                ->label('Kategori'),

            TextColumn::make('price')
                ->label('Harga')
                ->money('IDR'),

            TextColumn::make('stock')
                ->label('Stok'),

            IconColumn::make('is_recommended')
                ->label('Rekomendasi')
                ->boolean(),
            ])
            ->filters([
                //
                SelectFilter::make('category_id')
                ->label('Kategori')
                ->relationship('category', 'name'),

            ])
            ->actions([
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
            //
        ];
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Models\Promo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?string $navigationGroup = 'Marketing';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('is_active', true)->count();
        } catch (\Exception $e) {return null;       }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Promo')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Promo')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->helperText('Kode akan otomatis menjadi UPPERCASE')
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state)),

                        Forms\Components\TextInput::make('discount')
                            ->label('Potongan Harga')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(1000)
                            ->helperText('Nominal potongan (contoh: 10000 = Rp 10.000)'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Hanya promo aktif yang bisa digunakan'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Promo')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode disalin!')
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('discount')
                    ->label('Potongan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Digunakan')
                    ->counts('orders')
                    ->badge()
                    ->color('info')
                    ->suffix(' order'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleActive')
                    ->label(fn (Promo $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn (Promo $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Promo $record) => $record->is_active ? 'danger' : 'success')
                    ->action(fn (Promo $record) => $record->update(['is_active' => !$record->is_active])),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}

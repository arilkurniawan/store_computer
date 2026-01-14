<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Transaksi';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'invoice';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'waiting_confirmation')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        // Info Order
                        Forms\Components\Section::make('Informasi Pesanan')
                            ->schema([
                                Forms\Components\TextInput::make('invoice')
                                    ->label('No. Invoice')
                                    ->disabled(),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(Order::getStatuses())
                                    ->required(),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->disabled()
                                    ->prefix('Rp')
                                    ->numeric(),

                                Forms\Components\TextInput::make('discount')
                                    ->label('Diskon')
                                    ->disabled()
                                    ->prefix('Rp')
                                    ->numeric(),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total')
                                    ->disabled()
                                    ->prefix('Rp')
                                    ->numeric(),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Admin')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        // Info Pengiriman
                        Forms\Components\Section::make('Informasi Pengiriman')
                            ->schema([
                                Forms\Components\TextInput::make('shipping_name')
                                    ->label('Nama Penerima')
                                    ->disabled(),

                                Forms\Components\TextInput::make('shipping_phone')
                                    ->label('No. HP')
                                    ->disabled(),

                                Forms\Components\Textarea::make('shipping_address')
                                    ->label('Alamat')
                                    ->disabled()
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('shipping_province')
                                    ->label('Provinsi')
                                    ->disabled(),

                                Forms\Components\TextInput::make('shipping_city')
                                    ->label('Kota')
                                    ->disabled(),

                                Forms\Components\TextInput::make('shipping_postal_code')
                                    ->label('Kode Pos')
                                    ->disabled(),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpan(['lg' => 2]),

                // Sidebar
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Customer')
                            ->schema([
                                Forms\Components\Placeholder::make('user_name')
                                    ->label('Nama')
                                    ->content(fn (Order $record): string => $record->user->name ?? '-'),

                                Forms\Components\Placeholder::make('user_email')
                                    ->label('Email')
                                    ->content(fn (Order $record): string => $record->user->email ?? '-'),

                                Forms\Components\Placeholder::make('ordered_at')
                                    ->label('Tanggal Order')
                                    ->content(fn (Order $record): string => $record->created_at->format('d M Y H:i')),
                            ]),

                        Forms\Components\Section::make('Bukti Pembayaran')
                            ->schema([
                                Forms\Components\FileUpload::make('payment_proof')
                                    ->label('')
                                    ->image()
                                    ->directory('payment-proofs')
                                    ->openable()
                                    ->downloadable(),
                            ])
                            ->collapsible(),

                        Forms\Components\Section::make('Promo')
                            ->schema([
                                Forms\Components\Placeholder::make('promo_code')
                                    ->label('Kode Promo')
                                    ->content(fn (Order $record): string => $record->promo?->formatted_discount ?? '-'),

                                Forms\Components\Placeholder::make('promo_discount')
                                    ->label('Potongan')
                                    ->content(fn (Order $record): string => $record->promo ? $record->promo->formatted_discount : '-'),
                            ])
                            ->hidden(fn (Order $record): bool => !$record->promo_id),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'waiting_confirmation' => 'info',
                        'processing' => 'primary',
                        'shipped' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti')
                    ->circular()
                    ->defaultImageUrl(fn () => null),

                Tables\Columns\TextColumn::make('shipping_city')
                    ->label('Kota')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(Order::getStatuses()),

                Tables\Filters\Filter::make('has_payment_proof')
                    ->label('Ada Bukti Bayar')
                    ->query(fn ($query) => $query->whereNotNull('payment_proof')),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Quick Status Actions
                    Tables\Actions\Action::make('confirmPayment')
                        ->label('Payment Confirmation')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->visible(fn (Order $record) => $record->status === 'waiting_confirmation' && $record->payment_proof)
                        ->requiresConfirmation()
                        ->action(fn (Order $record) => $record->update(['status' => 'processing'])),

                    Tables\Actions\Action::make('markAsShipped')
                        ->label('Kirim Pesanan')
                        ->icon('heroicon-o-truck')
                        ->color('info')
                        ->visible(fn (Order $record) => $record->status === 'processing')
                        ->requiresConfirmation()
                        ->action(fn (Order $record) => $record->update(['status' => 'shipped'])),

                    Tables\Actions\Action::make('markAsCompleted')
                        ->label('Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Order $record) => $record->status === 'shipped')
                        ->requiresConfirmation()
                        ->action(fn (Order $record) => $record->update(['status' => 'completed'])),

                    Tables\Actions\Action::make('cancel')
                        ->label('Batalkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Order $record) => in_array($record->status, ['pending', 'waiting_confirmation', 'confirmed']))
                        ->requiresConfirmation()
                        ->action(fn (Order $record) => $record->update(['status' => 'cancelled'])),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make('Informasi Pesanan')
                            ->schema([
                                Infolists\Components\TextEntry::make('invoice')
                                    ->label('No. Invoice')
                                    ->weight('bold')
                                    ->copyable(),

                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state)
                                    ->color(fn (string $state): string => match($state) {
                                        'pending' => 'warning',
                                        'paid' => 'info',
                                        'processing' => 'primary',
                                        'shipped' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'secondary',
                                    }),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tanggal Order')
                                    ->dateTime('d M Y H:i'),
                            ])
                            ->columns(3),

                        Infolists\Components\Section::make('Ringkasan Harga')
                            ->schema([
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR', locale: 'id'),

                                Infolists\Components\TextEntry::make('discount')
                                    ->label('Diskon')
                                    ->money('IDR', locale: 'id')
                                    ->visible(fn (Order $record) => $record->discount > 0),

                                Infolists\Components\TextEntry::make('promo.code')
                                    ->label('Kode Promo')
                                    ->badge()
                                    ->color('success')
                                    ->visible(fn (Order $record) => $record->promo_id),

                                Infolists\Components\TextEntry::make('total')
                                    ->label('Total')
                                    ->money('IDR', locale: 'id')
                                    ->weight('bold')
                                    ->size('lg'),
                            ])
                            ->columns(4),

                        Infolists\Components\Section::make('Informasi Pengiriman')
                            ->schema([
                                Infolists\Components\TextEntry::make('shipping_name')
                                    ->label('Nama Penerima'),

                                Infolists\Components\TextEntry::make('shipping_phone')
                                    ->label('No. HP'),

                                Infolists\Components\TextEntry::make('shipping_address')
                                    ->label('Alamat')
                                    ->columnSpanFull(),

                                Infolists\Components\TextEntry::make('shipping_province')
                                    ->label('Provinsi'),

                                Infolists\Components\TextEntry::make('shipping_city')
                                    ->label('Kota'),

                                Infolists\Components\TextEntry::make('shipping_postal_code')
                                    ->label('Kode Pos'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpan(['lg' => 2]),

                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Section::make('Customer')
                            ->schema([
                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Nama'),

                                Infolists\Components\TextEntry::make('user.email')
                                    ->label('Email'),
                            ]),

                        Infolists\Components\Section::make('Bukti Pembayaran')
                            ->schema([
                                Infolists\Components\ImageEntry::make('payment_proof')
                                    ->label('')
                                    ->height(200),
                            ])
                            ->visible(fn (Order $record) => $record->payment_proof),

                        Infolists\Components\Section::make('Catatan')
                            ->schema([
                                Infolists\Components\TextEntry::make('notes')
                                    ->label('')
                                    ->placeholder('Tidak ada catatan'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

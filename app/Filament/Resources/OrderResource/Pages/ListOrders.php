<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Menunggu Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(fn () => \App\Models\Order::where('status', 'pending')->count())
                ->badgeColor('warning'),
            'waiting_confirmation' => Tab::make('Menunggu Konfirmasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'waiting_confirmation'))
                ->badge(fn () => \App\Models\Order::where('status', 'waiting_confirmation')->count())
                ->badgeColor('info'),
            'processing' => Tab::make('Diproses')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing'))    
                ->badge(fn () => \App\Models\Order::where('status', 'processing')->count())
                ->badgeColor('primary'),
            'shipped' => Tab::make('Dikirim')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'shipped')),
            'completed' => Tab::make('Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed')),
            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled')),
        ];
    }
}

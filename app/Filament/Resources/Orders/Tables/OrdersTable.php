<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Belanja')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Processing' => 'info',
                        'Completed' => 'success',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('checkoutMeta.delivery_method')
                    ->label('Pengiriman')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pickup' => 'Pickup',
                        'instant' => 'Ojol',
                        default => '-',
                    })
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pickup' => 'warning',
                        'instant' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('checkoutMeta.payment_method')
                    ->label('Pembayaran')
                    ->formatStateUsing(fn (?string $state): string => $state ? strtoupper($state) : '-')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('checkoutMeta.paid_at')
                    ->label('Paid At')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),

                TextColumn::make('checkoutMeta.expired_at')
                    ->label('Expired At')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),

                TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Pesan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('delivery_method')
                    ->label('Pengiriman')
                    ->relationship('checkoutMeta', 'delivery_method')
                    ->options([
                        'pickup' => 'Pickup',
                        'instant' => 'Ojol',
                    ]),
                SelectFilter::make('payment_method')
                    ->label('Pembayaran')
                    ->relationship('checkoutMeta', 'payment_method')
                    ->options([
                        'qris' => 'QRIS',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([])
            ->poll('3s');
    }
}

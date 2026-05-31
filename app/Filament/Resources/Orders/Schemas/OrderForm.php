<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pelanggan')
                    ->disabled()
                    ->dehydrated(false)
                    ->required(),

                Placeholder::make('total_amount_display')
                    ->label('Total Belanja')
                    ->content(static fn ($record): string => 'Rp. ' . number_format((float) ($record?->total_amount ?? 0), 0, ',', '.')),

                Select::make('status')
                    ->label('Status Pesanan')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Diproses',
                        'Completed' => 'Selesai',
                        'Cancelled' => 'Dibatalkan',
                    ])
                    ->default('Pending'),

                Placeholder::make('payment_info')
                    ->label('Info Pembayaran')
                    ->content(static function ($record): string {
                        if (! $record?->checkoutMeta) {
                            return 'Belum ada metadata pembayaran.';
                        }

                        $method = strtoupper((string) ($record->checkoutMeta->payment_method ?? '-'));
                        $paidAtRaw = $record->checkoutMeta->paid_at;
                        $expiredAtRaw = $record->checkoutMeta->expired_at;

                        $paidAt = $paidAtRaw ? Carbon::parse($paidAtRaw)->format('d M Y, H:i') : '-';
                        $expiredAt = $expiredAtRaw ? Carbon::parse($expiredAtRaw)->format('d M Y, H:i') : '-';

                        return "Metode: {$method} | Paid At: {$paidAt} | Expired At: {$expiredAt}";
                    }),
            ]);
    }
}

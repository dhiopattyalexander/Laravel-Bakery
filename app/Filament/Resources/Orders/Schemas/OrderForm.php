<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

                \Filament\Schemas\Components\Fieldset::make('Detail Checkout')
                    ->relationship('checkoutMeta')
                    ->schema([
                        TextInput::make('delivery_method')->disabled(),
                        TextInput::make('payment_method')->disabled(),
                        TextInput::make('pickup_time')->disabled(),
                        \Filament\Forms\Components\Textarea::make('shipping_address')->disabled()->columnSpanFull(),
                        \Filament\Forms\Components\Textarea::make('order_notes')->disabled()->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\ViewAvailableBread;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class AvailableBreadsWidget extends BaseWidget
{
    protected static ?string $heading = 'Roti Tersedia (Dari SQL View)';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return ViewAvailableBread::query()->orderBy('name');
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Roti')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
            ]);
    }
}

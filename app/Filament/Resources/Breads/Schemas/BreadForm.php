<?php

namespace App\Filament\Resources\Breads\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BreadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->required()
                    ->relationship('category', 'name')
                    ->searchable()
                    ->label('Kategori'),
                
                TextInput::make('name')
                    ->required()
                    ->label('Nama Roti'),
                
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Deskripsi'),
                
                FileUpload::make('image_path')
                    ->image() // Validasi file gambar
                    ->disk('public') // PAKSA MASUK KE DISK PUBLIC
                    ->directory('roti-images')
                    ->columnSpanFull()
                    ->label('Foto Roti'),
                
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp') // Ubah jadi Rupiah
                    ->label('Harga'),
                
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Stok'),
            ]);
    }
}
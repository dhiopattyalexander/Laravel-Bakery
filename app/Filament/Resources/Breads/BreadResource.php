<?php

namespace App\Filament\Resources\Breads;

use App\Filament\Resources\Breads\Pages\CreateBread;
use App\Filament\Resources\Breads\Pages\EditBread;
use App\Filament\Resources\Breads\Pages\ListBreads;
use App\Filament\Resources\Breads\Schemas\BreadForm;
use App\Filament\Resources\Breads\Tables\BreadsTable;
use App\Models\Bread;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BreadResource extends Resource
{
    protected static ?string $model = Bread::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Produk';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        // Mengembalikan ke struktur aslinya, memanggil file BreadForm.php
        return BreadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BreadsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('view_any_bread') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('create_bread') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('update_bread') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('delete_bread') ?? false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBreads::route('/'),
            'create' => CreateBread::route('/create'),
            'edit' => EditBread::route('/{record}/edit'),
        ];
    }
}
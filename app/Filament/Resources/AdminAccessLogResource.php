<?php

namespace App\Filament\Resources;

use App\Models\AdminAccessLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class AdminAccessLogResource extends Resource
{
    protected static ?string $model = AdminAccessLog::class;

    protected static ?string $slug = 'admin-access-logs';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Log Akses Admin';

    protected static ?string $modelLabel = 'Log Akses';

    protected static ?string $pluralModelLabel = 'Log Akses Admin';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('accessed_at')
                    ->label('Waktu Akses')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Admin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (AdminAccessLog $record): string => $record->user_agent),
            ])
            ->defaultSort('accessed_at', 'desc')
            ->recordActions([
                // No actions to ensure read-only behavior
            ])
            ->toolbarActions([
                // No bulk actions to ensure read-only behavior
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\AdminAccessLogResource\Pages\ListAdminAccessLogs::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('User Details')->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required(),
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    Select::make('roles')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload(),
                    CheckboxList::make('permissions')
                        ->label('Izin Langsung (Direct Permissions)')
                        ->relationship('permissions', 'name')
                        ->columns(2)
                        ->bulkToggleable(),
                ])->columns(2),

                \Filament\Schemas\Components\Section::make('Profile')->relationship('profile')->schema([
                    TextInput::make('phone')->tel(),
                    \Filament\Forms\Components\DatePicker::make('birth_date'),
                    Select::make('gender')->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ]),
                    \Filament\Forms\Components\Textarea::make('address')->columnSpanFull(),
                ])->columns(3),
            ]);
    }
}

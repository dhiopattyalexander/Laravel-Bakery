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
                        ->revealable()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->confirmed()
                        ->helperText(fn (string $operation) => $operation === 'edit' ? 'Kosongkan jika tidak ingin mengubah password.' : ''),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->revealable()
                        ->label('Konfirmasi Password')
                        ->dehydrated(false)
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    Select::make('roles')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->preload()
                        ->live(),
                    CheckboxList::make('permissions')
                        ->label('Izin Langsung (Direct Permissions) *yang diredupkan berarti sudah bawaan dari Role')
                        ->relationship('permissions', 'name')
                        ->columns(3)
                        ->bulkToggleable()
                        ->disableOptionWhen(function (string $value, $get): bool {
                            $roles = $get('roles');
                            if (empty($roles)) {
                                return false;
                            }
                            // Dapatkan ID permissions bawaan dari role yang dipilih
                            $rolePermissions = \Spatie\Permission\Models\Role::whereIn('id', $roles)
                                ->with('permissions')
                                ->get()
                                ->pluck('permissions.*.id')
                                ->flatten()
                                ->unique()
                                ->toArray();
                            
                            return in_array($value, $rolePermissions);
                        }),
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

<?php

namespace App\Filament\Resources\AdminAccessLogResource\Pages;

use App\Filament\Resources\AdminAccessLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAdminAccessLogs extends ListRecords
{
    protected static string $resource = AdminAccessLogResource::class;

    protected function getHeaderActions(): array
    {
        return []; // No create action to ensure read-only behavior
    }
}

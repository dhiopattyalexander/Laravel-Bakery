<?php

namespace App\Filament\Resources\Breads\Pages;

use App\Filament\Resources\Breads\BreadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBreads extends ListRecords
{
    protected static string $resource = BreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

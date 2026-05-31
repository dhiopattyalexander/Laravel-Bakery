<?php

namespace App\Filament\Resources\Breads\Pages;

use App\Filament\Resources\Breads\BreadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBread extends EditRecord
{
    protected static string $resource = BreadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

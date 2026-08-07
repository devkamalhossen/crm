<?php

namespace App\Filament\Resources\ClientServices\Pages;

use App\Filament\Resources\ClientServices\ClientServiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClientService extends ViewRecord
{
    protected static string $resource = ClientServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

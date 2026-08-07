<?php

namespace App\Filament\Resources\ClientServices\Pages;

use App\Filament\Resources\ClientServices\ClientServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClientServices extends ListRecords
{
    protected static string $resource = ClientServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

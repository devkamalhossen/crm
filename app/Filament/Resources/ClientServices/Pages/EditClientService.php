<?php

namespace App\Filament\Resources\ClientServices\Pages;

use App\Filament\Resources\ClientServices\ClientServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditClientService extends EditRecord
{
    protected static string $resource = ClientServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

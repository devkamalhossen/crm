<?php

namespace App\Filament\Resources\ClientServices\Pages;

use App\Filament\Resources\ClientServices\ClientServiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClientService extends ViewRecord
{
    protected static string $resource = ClientServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back')
                ->color('gray')
                ->url(ClientServiceResource::getUrl('index')),
            EditAction::make(),
        ];
    }
}

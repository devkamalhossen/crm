<?php

namespace App\Filament\Resources\SalesTeams\Pages;

use App\Filament\Resources\SalesTeams\SalesTeamResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesTeam extends ViewRecord
{
    protected static string $resource = SalesTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

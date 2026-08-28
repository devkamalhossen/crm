<?php

namespace App\Filament\Resources\SalesTeams\Pages;

use App\Filament\Resources\SalesTeams\SalesTeamResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesTeam extends EditRecord
{
    protected static string $resource = SalesTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

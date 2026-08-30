<?php

namespace App\Filament\Resources\ProjectMeetings\Pages;

use App\Filament\Resources\ProjectMeetings\ProjectMeetingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectMeetings extends ListRecords
{
    protected static string $resource = ProjectMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

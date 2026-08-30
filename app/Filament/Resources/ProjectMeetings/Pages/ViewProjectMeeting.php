<?php

namespace App\Filament\Resources\ProjectMeetings\Pages;

use App\Filament\Resources\ProjectMeetings\ProjectMeetingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectMeeting extends ViewRecord
{
    protected static string $resource = ProjectMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

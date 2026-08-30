<?php

namespace App\Filament\Resources\ProjectMeetings\Pages;

use App\Filament\Resources\ProjectMeetings\ProjectMeetingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectMeeting extends EditRecord
{
    protected static string $resource = ProjectMeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

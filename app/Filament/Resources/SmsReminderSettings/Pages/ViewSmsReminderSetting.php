<?php

namespace App\Filament\Resources\SmsReminderSettings\Pages;

use App\Filament\Resources\SmsReminderSettings\SmsReminderSettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSmsReminderSetting extends ViewRecord
{
    protected static string $resource = SmsReminderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SmsReminderSettings\Pages;

use App\Filament\Resources\SmsReminderSettings\SmsReminderSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSmsReminderSetting extends EditRecord
{
    protected static string $resource = SmsReminderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

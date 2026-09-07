<?php

namespace App\Filament\Resources\SmsReminderSettings\Pages;

use App\Filament\Resources\SmsReminderSettings\SmsReminderSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSmsReminderSettings extends ListRecords
{
    protected static string $resource = SmsReminderSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

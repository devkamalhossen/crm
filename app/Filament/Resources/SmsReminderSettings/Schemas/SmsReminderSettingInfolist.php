<?php

namespace App\Filament\Resources\SmsReminderSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SmsReminderSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('trigger_type')
                    ->badge(),
                TextEntry::make('days')
                    ->numeric(),
                TextEntry::make('message')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

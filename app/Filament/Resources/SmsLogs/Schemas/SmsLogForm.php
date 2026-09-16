<?php

namespace App\Filament\Resources\SmsLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SmsLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->relationship('invoice', 'id')
                    ->default(null),
                TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('sms_reminder_setting_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                Select::make('trigger_type')
                    ->options(['before_due' => 'Before due', 'after_due' => 'After due', 'manual' => 'Manual'])
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'sent' => 'Sent', 'failed' => 'Failed'])
                    ->default('pending')
                    ->required(),
                DateTimePicker::make('sent_at'),
                Textarea::make('provider_response')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('error_message')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}

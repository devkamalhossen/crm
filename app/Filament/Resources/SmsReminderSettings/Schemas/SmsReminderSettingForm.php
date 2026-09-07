<?php

namespace App\Filament\Resources\SmsReminderSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SmsReminderSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SMS Reminder Settings')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Reminder Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Payment Due Reminder'),

                                Select::make('trigger_type')
                                    ->label('Trigger Type')
                                    ->options([
                                        'before_due' => 'Before Due Date',
                                        'after_due' => 'After Due Date',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->live(),

                                TextInput::make('days')
                                    ->label('Days')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->required()
                                    ->helperText(
                                        'Number of days before or after the invoice due date.'
                                    ),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active')
                                    ->required()
                                    ->native(false),
                            ]),

                        Textarea::make('message')
                                ->label('SMS Message')
                                ->required()
                                ->rows(5)
                                ->maxLength(1000)
                                ->columnSpanFull()
                                ->placeholder(
                                    'Dear {client_name}, your invoice {invoice_number} has an outstanding due of {due_amount}. Due date: {due_date}.'
                                )
                                ->helperText('You can use dynamic placeholders such as client name, invoice number, due amount, and due date.'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ]);
    }
}
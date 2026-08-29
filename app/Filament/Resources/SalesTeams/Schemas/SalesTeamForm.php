<?php

namespace App\Filament\Resources\SalesTeams\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalesTeamForm
{
    public static function configure(Schema $schema): Schema
    {
       return $schema
            ->components([
                Section::make('Sales Person Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('employee_id')
                                    ->label('Employee ID')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('designation')
                                    ->label('Designation')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('mobile_number')
                                    ->label('Mobile Number')
                                    ->tel()
                                    ->maxLength(20),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                                DatePicker::make('joining_date')
                                    ->label('Joining Date'),

                            ]),

                            Section::make('Assign Client Services')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'active' => 'Active',
                                                'inactive' => 'Inactive',
                                            ])
                                            ->default('active')
                                            ->required(),
                                        
                                        Select::make('clientServices')
                                            ->label('Client Services')
                                            ->relationship(
                                                name: 'clientServices',
                                                titleAttribute: 'id',
                                            )
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->getOptionLabelFromRecordUsing(function ($record) {
                                                $clientName = $record->client?->company_name ?? $record->client?->name ?? 'Unknown Client';
                                                return $clientName . ' - ' . ucwords(str_replace('_', ' ', $record->service_type));
                                            })
                                            ->helperText('Select the client services assigned to this salesperson.'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
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
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}

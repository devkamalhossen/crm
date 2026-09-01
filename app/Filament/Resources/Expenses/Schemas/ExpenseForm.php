<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expense Details')
                    ->description('Record and track business expenses.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Expense Title')
                            ->placeholder('e.g. Office Rent, Internet Bill')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0)
                            ->step(0.01)
                            ->required(),

                        DatePicker::make('expense_date')
                            ->label('Expense Date')
                            ->native(false)
                            ->default(now())
                            ->required(),

                        TextInput::make('category')
                            ->label('Category')
                            ->placeholder('e.g. Salary, Office, Utility, Marketing')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Add any additional details about this expense...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
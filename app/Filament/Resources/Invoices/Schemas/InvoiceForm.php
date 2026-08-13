<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('client_service_id')
                    ->relationship('clientService', 'id')
                    ->default(null),
                TextInput::make('invoice_number')
                    ->required(),
                DatePicker::make('invoice_date')
                    ->required(),
                DatePicker::make('due_date'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options([
            'draft' => 'Draft',
            'unpaid' => 'Unpaid',
            'partial' => 'Partial',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled',
        ])
                    ->default('draft')
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Invoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;


class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                  Section::make('Payment Information')
                    ->schema([

                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship(
                                name: 'invoice',
                                titleAttribute: 'invoice_number',
                                modifyQueryUsing: fn ($query) =>
                                    $query
                                        ->whereIn('status', [
                                            'unpaid',
                                            'partial',
                                            'overdue',
                                        ])
                                        ->with('client')
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Invoice $record) =>
                                    "{$record->invoice_number} — {$record->client->name} — ৳" .
                                    number_format($record->total_amount, 2)
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (! $state) {
                                    $set('user_id', null);
                                    return;
                                }

                                $invoice = Invoice::with('client')->find($state);

                                $set('user_id', $invoice?->user_id);
                            })
                            ->required(),


                        Select::make('user_id')
                            ->label('Client')
                            ->relationship(
                                name: 'client',
                                titleAttribute: 'name'
                            )
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),

                        TextInput::make('amount')
                            ->label('Payment Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0.01)
                            ->required(),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'bank_transfer' => 'Bank Transfer',
                                'bkash' => 'bKash',
                                'cash' => 'Cash',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('completed')
                            ->required(),

                        TextInput::make('payment_reference')
                            ->label('Payment Reference')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->maxLength(255),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),

                    ])
                    ->columns(2)->columnSpanFull(),
            ]);
    }
}

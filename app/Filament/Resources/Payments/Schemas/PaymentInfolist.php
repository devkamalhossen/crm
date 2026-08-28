<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Invoice & Client Information
                |--------------------------------------------------------------------------
                */

                Section::make('Invoice & Client Information')
                    ->schema([

                        TextEntry::make('invoice.invoice_number')
                            ->label('Invoice Number')
                            ->weight('bold')
                            ->copyable(),

                        TextEntry::make('client.name')
                            ->label('Client Name')
                            ->placeholder('-'),

                        TextEntry::make('client.company_name')
                            ->label('Company')
                            ->placeholder('-'),

                        TextEntry::make('client.email')
                            ->label('Email')
                            ->placeholder('-'),

                        TextEntry::make('client.phone')
                            ->label('Phone')
                            ->placeholder('-'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Payment Information
                |--------------------------------------------------------------------------
                */

                Section::make('Payment Information')
                    ->schema([

                        TextEntry::make('payment_reference')
                            ->label('Payment Reference')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('payment_date')
                            ->label('Payment Date')
                            ->date('d M Y'),

                        TextEntry::make('amount')
                            ->label('Amount')
                            ->money('BDT')
                            ->weight('bold'),

                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'bank_transfer' => 'Bank Transfer',
                                    'bkash' => 'bKash',
                                    'cash' => 'Cash',
                                    default => $state
                                        ? ucfirst(str_replace('_', ' ', $state))
                                        : '-',
                                }
                            ),

                        TextEntry::make('status')
                            ->label('Payment Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => $state
                                    ? ucfirst($state)
                                    : '-'
                            ),

                        TextEntry::make('transaction_id')
                            ->label('Transaction ID')
                            ->placeholder('-')
                            ->copyable(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Received By
                |--------------------------------------------------------------------------
                */

                Section::make('Received By')
                    ->schema([

                        TextEntry::make('receivedBy.name')
                            ->label('Received By')
                            ->placeholder('-'),

                        TextEntry::make('receivedBy.email')
                            ->label('Email')
                            ->placeholder('-'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Additional Information
                |--------------------------------------------------------------------------
                */

                Section::make('Additional Information')
                    ->schema([

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, h:i A')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('d M Y, h:i A')
                            ->placeholder('-'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}

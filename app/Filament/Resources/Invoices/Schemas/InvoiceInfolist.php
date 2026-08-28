<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Invoice Information
                |--------------------------------------------------------------------------
                */

                Section::make('Invoice Information')
                    ->schema([

                        TextEntry::make('invoice_number')
                            ->label('Invoice Number')
                            ->weight('bold'),

                        TextEntry::make('invoice_date')
                            ->label('Invoice Date')
                            ->date('d M Y'),

                        TextEntry::make('due_date')
                            ->label('Due Date')
                            ->date('d M Y')
                            ->placeholder('—'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (string $state): string =>
                                    ucfirst($state)
                            )
                            ->color(
                                fn (string $state): string => match ($state) {
                                    'draft' => 'gray',
                                    'unpaid' => 'warning',
                                    'partial' => 'info',
                                    'paid' => 'success',
                                    'overdue' => 'danger',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                }
                            ),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Client Information
                |--------------------------------------------------------------------------
                */

                Section::make('Client Information')
                    ->schema([

                        TextEntry::make('client.name')
                            ->label('Client')
                            ->placeholder('—'),

                        TextEntry::make('client.company_name')
                            ->label('Company')
                            ->placeholder('—'),

                        TextEntry::make('client.email')
                            ->label('Email')
                            ->placeholder('—'),

                        TextEntry::make('client.phone')
                            ->label('Phone')
                            ->placeholder('—'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Service Information
                |--------------------------------------------------------------------------
                */

                Section::make('Service Information')
                    ->schema([

                        TextEntry::make('clientService.service_type')
                            ->label('Service Type')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'seo' => 'SEO',
                                    'website' => 'Website Development',
                                    'digital_marketing' => 'Digital Marketing',
                                    default => $state
                                        ? ucfirst(str_replace('_', ' ', $state))
                                        : '—',
                                }
                            )
                            ->placeholder('—'),

                        TextEntry::make('clientService.payment_type')
                            ->label('Payment Type')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'monthly' => 'Monthly',
                                    'project_based' => 'Project Based',
                                    'yearly' => 'Yearly',
                                    default => $state
                                        ? ucfirst(str_replace('_', ' ', $state))
                                        : '—',
                                }
                            )
                            ->placeholder('—'),

                        TextEntry::make('clientService.project_status')
                            ->label('Project Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'delivered' => 'Delivered',
                                    default => '—',
                                }
                            )
                            ->placeholder('—'),

                        TextEntry::make('clientService.start_date')
                            ->label('Service Start Date')
                            ->date('d M Y')
                            ->placeholder('—'),

                        TextEntry::make('clientService.end_date')
                            ->label('Service End Date')
                            ->date('d M Y')
                            ->placeholder('—'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Financial Information
                |--------------------------------------------------------------------------
                */

                Section::make('Financial Information')
                    ->schema([

                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('BDT'),

                        TextEntry::make('discount_type')
                            ->label('Discount Type')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string =>
                                    $state
                                        ? ucfirst($state)
                                        : '—'
                            ),

                        TextEntry::make('discount_value')
                            ->label('Discount Value')
                            ->numeric(decimalPlaces: 2),

                        TextEntry::make('discount_amount')
                            ->label('Discount Amount')
                            ->money('BDT'),

                        TextEntry::make('tax')
                            ->label('Tax')
                            ->money('BDT'),

                        TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('BDT')
                            ->weight('bold'),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Invoice Items
                |--------------------------------------------------------------------------
                */

                Section::make('Invoice Items')
                    ->schema([

                        TextEntry::make('items')
                            ->label('')
                            ->state(function ($record) {

                                return $record->items
                                    ->map(function ($item) {

                                        return sprintf(
                                            "%s | Qty: %s | Unit Price: ৳%s | Amount: ৳%s",
                                            $item->item_name,
                                            $item->quantity,
                                            number_format(
                                                $item->unit_price,
                                                2
                                            ),
                                            number_format(
                                                $item->amount,
                                                2
                                            )
                                        );

                                    })
                                    ->join("\n");
                            })
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Payment Summary
                |--------------------------------------------------------------------------
                */

                Section::make('Payment Summary')
                    ->schema([

                        TextEntry::make('paid_amount')
                            ->label('Paid Amount')
                            ->state(
                                fn ($record) =>
                                    $record->paid_amount
                            )
                            ->money('BDT')
                            ->color('success'),

                        TextEntry::make('due_amount')
                            ->label('Due Amount')
                            ->state(
                                fn ($record) =>
                                    $record->due_amount
                            )
                            ->money('BDT')
                            ->color('danger'),

                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->state(
                                fn ($record) =>
                                    ucfirst($record->payment_status)
                            )
                            ->badge(),

                        TextEntry::make('last_payment_date')
                            ->label('Last Payment Date')
                            ->state(
                                fn ($record) =>
                                    $record->payments()
                                        ->where('status', 'completed')
                                        ->latest('payment_date')
                                        ->value('payment_date')
                            )
                            ->date('d M Y')
                            ->placeholder('-'),

                    ])
                    ->columns(4)
                    ->columnSpanFull(),


                /*
                |--------------------------------------------------------------------------
                | Notes
                |--------------------------------------------------------------------------
                */

                Section::make('Additional Information')
                    ->schema([

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes available.')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Timestamps
                |--------------------------------------------------------------------------
                */

                Section::make('System Information')
                    ->schema([

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, h:i A'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d M Y, h:i A'),

                    ])
                    ->columns(2)
                    ->collapsed()
                    ->columnSpanFull(),

            ]);
    }
}
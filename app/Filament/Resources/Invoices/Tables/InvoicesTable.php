<?php

namespace App\Filament\Resources\Invoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class InvoicesTable
{
    // public static function configure(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             TextColumn::make('user_id')
    //                 ->numeric()
    //                 ->sortable(),
    //             TextColumn::make('clientService.id')
    //                 ->searchable(),
    //             TextColumn::make('invoice_number')
    //                 ->searchable(),
    //             TextColumn::make('invoice_date')
    //                 ->date()
    //                 ->sortable(),
    //             TextColumn::make('due_date')
    //                 ->date()
    //                 ->sortable(),
    //             TextColumn::make('subtotal')
    //                 ->numeric()
    //                 ->sortable(),
    //             TextColumn::make('discount')
    //                 ->numeric()
    //                 ->sortable(),
    //             TextColumn::make('tax')
    //                 ->numeric()
    //                 ->sortable(),
    //             TextColumn::make('total_amount')
    //                 ->numeric()
    //                 ->sortable(),
    //             TextColumn::make('status')
    //                 ->badge(),
    //             TextColumn::make('created_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //             TextColumn::make('updated_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->recordActions([
    //             ViewAction::make(),
    //             EditAction::make(),
    //         ])
    //         ->toolbarActions([
    //             BulkActionGroup::make([
    //                 DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }



    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                /*
                |--------------------------------------------------------------------------
                | Invoice Number
                |--------------------------------------------------------------------------
                */

                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                /*
                |--------------------------------------------------------------------------
                | Client
                |--------------------------------------------------------------------------
                */

                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('client.company_name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable(),

                /*
                |--------------------------------------------------------------------------
                | Service
                |--------------------------------------------------------------------------
                */

                TextColumn::make('clientService.service_type')
                    ->label('Service')
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
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Invoice Date
                |--------------------------------------------------------------------------
                */

                TextColumn::make('invoice_date')
                    ->label('Invoice Date')
                    ->date('d M Y')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Due Date
                |--------------------------------------------------------------------------
                */

                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('d M Y')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Subtotal
                |--------------------------------------------------------------------------
                */

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('BDT')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Discount
                |--------------------------------------------------------------------------
                */

                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->money('BDT')
                    ->sortable()
                    ->toggleable(),

                /*
                |--------------------------------------------------------------------------
                | Tax
                |--------------------------------------------------------------------------
                */

                TextColumn::make('tax')
                    ->label('Tax')
                    ->money('BDT')
                    ->sortable()
                    ->toggleable(),

                /*
                |--------------------------------------------------------------------------
                | Total
                |--------------------------------------------------------------------------
                */

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('BDT')
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Paid Amount
                |--------------------------------------------------------------------------
                */

                TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money('BDT')
                    ->state(
                        fn ($record): float => $record->paid_amount
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Due Amount
                |--------------------------------------------------------------------------
                */

                TextColumn::make('due_amount')
                    ->label('Due')
                    ->money('BDT')
                    ->state(
                        fn ($record): float => $record->due_amount
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Payment Status
                |--------------------------------------------------------------------------
                */

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->state(
                        fn ($record): string => $record->payment_status
                    )
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'paid' => 'Paid',
                            'partial' => 'Partial',
                            'overdue' => 'Overdue',
                            'unpaid' => 'Unpaid',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($state),
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'paid' => 'success',
                            'partial' => 'warning',
                            'overdue' => 'danger',
                            'unpaid' => 'gray',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Invoice Status
                |--------------------------------------------------------------------------
                */

                TextColumn::make('status')
                    ->label('Invoice Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'draft' => 'Draft',
                            'unpaid' => 'Unpaid',
                            'partial' => 'Partial',
                            'paid' => 'Paid',
                            'overdue' => 'Overdue',
                            'cancelled' => 'Cancelled',
                            default => ucfirst($state),
                        }
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
                    )
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | Created At
                |--------------------------------------------------------------------------
                */

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),

                /*
                |--------------------------------------------------------------------------
                | Updated At
                |--------------------------------------------------------------------------
                */

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),
            ])

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            ->filters([

                SelectFilter::make('status')
                    ->label('Invoice Status')
                    ->options([
                        'draft' => 'Draft',
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('service_type')
                    ->label('Service')
                    ->options([
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, $value) =>
                                $query->whereHas(
                                    'clientService',
                                    fn (Builder $query) =>
                                        $query->where('service_type', $value)
                                )
                        );
                    }),

                SelectFilter::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage',
                    ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | Record Actions
            |--------------------------------------------------------------------------
            */

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            /*
            |--------------------------------------------------------------------------
            | Bulk Actions
            |--------------------------------------------------------------------------
            */

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | Default Sorting
            |--------------------------------------------------------------------------
            */

            ->defaultSort(
                'invoice_date',
                'desc'
            );
    }
}

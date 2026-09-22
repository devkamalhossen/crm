<?php

namespace App\Filament\Resources\ClientServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                  TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()->limit(30),

                TextColumn::make('client.company_name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable()->limit(30),

                TextColumn::make('service_type')
                    ->label('Service')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->sortable(),

                TextColumn::make('payment_type')
                    ->label('Payment Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'monthly' => 'Monthly',
                        'project_based' => 'Project Based',
                        'yearly' => 'Yearly',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('advance_amount')
                    ->label('Advance')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('installment_months')
                    ->label('Installments')
                    ->suffix(' months')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('project_status')
                    ->label('Project Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                        default => '—',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'in_progress' => 'warning',
                        'completed' => 'info',
                        'delivered' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
            ])
            ->filters([
                // service type wise filter
                SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                    ]),

                // payment type wise filter
                SelectFilter::make('payment_type')
                    ->label('Payment Type')
                    ->options([
                        'monthly' => 'Monthly',
                        'project_based' => 'Project Based',
                        'yearly' => 'Yearly',
                    ]),

                // project status wise filter
                SelectFilter::make('project_status')
                    ->label('Project Status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                    ]),

                // service status wise filter
                SelectFilter::make('status')
                    ->label('Service Status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])->recordActionsColumnLabel('Action')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

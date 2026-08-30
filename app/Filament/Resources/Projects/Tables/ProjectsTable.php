<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('clientService.client.company_name')
                    ->label('Client')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->clientService?->client?->company_name
                            ?? $record->clientService?->client?->name
                            ?? 'Unknown Client';
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('clientService.service_type')
                    ->label('Service')
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? ucwords(str_replace('_', ' ', $state))
                            : '-';
                    })
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('projectManager.name')
                    ->label('Project Manager')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Not Assigned'),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('duration_months')
                    ->label('Duration')
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? $state . ' ' . ($state == 1 ? 'Month' : 'Months')
                            : '-';
                    })
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'not_started' => 'gray',
                        'in_progress' => 'info',
                        'completed'   => 'success',
                        'delivered'   => 'success',
                        'on_hold'     => 'warning',
                        'cancelled'   => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        ucwords(str_replace('_', ' ', $state))
                    ),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->label('Project Status')
                    ->options([
                        'not_started' => 'Not Started',
                        'in_progress' => 'In Progress',
                        'completed'   => 'Completed',
                        'delivered'   => 'Delivered',
                        'on_hold'     => 'On Hold',
                        'cancelled'   => 'Cancelled',
                    ]),

                SelectFilter::make('project_manager_id')
                    ->label('Project Manager')
                    ->relationship(
                        name: 'projectManager',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('client_service_id')
                    ->label('Client Service')
                    ->options(function () {
                        return \App\Models\ClientService::query()
                            ->with('client')
                            ->get()
                            ->mapWithKeys(function ($service) {
                                $clientName = $service->client?->company_name
                                    ?? $service->client?->name
                                    ?? 'Unknown Client';

                                $serviceName = ucwords(
                                    str_replace('_', ' ', $service->service_type)
                                );

                                return [
                                    $service->id => "{$clientName} - {$serviceName}",
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload(),


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

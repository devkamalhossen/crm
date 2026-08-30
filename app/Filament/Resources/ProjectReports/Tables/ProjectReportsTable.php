<?php

namespace App\Filament\Resources\ProjectReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Client
                TextColumn::make('project.clientService.client.company_name')
                    ->label('Client')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->project?->clientService?->client?->company_name
                            ?? $record->project?->clientService?->client?->name
                            ?? 'Unknown Client';
                    })
                    ->searchable()
                    ->sortable(),

                // Service
                TextColumn::make('project.clientService.service_type')
                    ->label('Service')
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? ucwords(str_replace('_', ' ', $state))
                            : '-';
                    })
                    ->badge()
                    ->searchable()
                    ->sortable(),

                // Project Manager
                TextColumn::make('project.projectManager.name')
                    ->label('Project Manager')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Not Assigned'),

                // Report Type
                TextColumn::make('report_type')
                    ->label('Report Type')
                    ->formatStateUsing(fn (string $state): string =>
                        ucwords(str_replace('_', ' ', $state))
                    )
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'weekly' => 'info',
                        'bi_weekly' => 'warning',
                        'monthly' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                // Report Date
                TextColumn::make('report_date')
                    ->label('Report Date')
                    ->date('d M Y')
                    ->sortable(),

                // Due Date
                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('d M Y')
                    ->sortable(),

                // Status
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'overdue' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string =>
                        ucfirst($state)
                    )
                    ->sortable(),

                // Completed At
                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->placeholder('-'),

                // Report Link
                TextColumn::make('report_link')
                    ->label('Report')
                    ->formatStateUsing(fn ($state) =>
                        $state ? 'View Report' : '-'
                    )
                    ->url(fn ($record) => $record->report_link)
                    ->openUrlInNewTab()
                    ->placeholder('-'),

                // Created At
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Updated At
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                SelectFilter::make('report_type')
                    ->label('Report Type')
                    ->options([
                        'weekly' => 'Weekly',
                        'bi_weekly' => 'Bi-Weekly',
                        'monthly' => 'Monthly',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'overdue' => 'Overdue',
                    ]),

                SelectFilter::make('project_manager_id')
                    ->label('Project Manager')
                    ->relationship(
                        name: 'project.projectManager',
                        titleAttribute: 'name',
                    )
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

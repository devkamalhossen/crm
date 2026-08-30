<?php

namespace App\Filament\Resources\ProjectMeetings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectMeetingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Project / Client
                TextColumn::make('project.id')
                    ->label('Project')
                    ->formatStateUsing(function ($state, $record) {
                        $client = $record->project?->clientService?->client;

                        $clientName = $client?->company_name
                            ?? $client?->name
                            ?? 'Unknown Client';

                        $serviceType = $record->project?->clientService?->service_type
                            ? ucwords(str_replace(
                                '_',
                                ' ',
                                $record->project->clientService->service_type
                            ))
                            : 'Unknown Service';

                        return 'Project #' . $state
                            . ' - '
                            . $clientName
                            . ' - '
                            . $serviceType;
                    })
                    ->searchable(
                        query: function ($query, string $search): void {
                            $query->whereHas(
                                'project.clientService.client',
                                function ($query) use ($search) {
                                    $query
                                        ->where('name', 'like', "%{$search}%")
                                        ->orWhere('company_name', 'like', "%{$search}%");
                                }
                            );
                        }
                    )
                    ->sortable(),

                // Related Report
                TextColumn::make('projectReport.report_type')
                    ->label('Report')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->projectReport) {
                            return 'No Report';
                        }

                        $type = ucwords(str_replace('_', ' ', $state));

                        $date = $record->projectReport->report_date?->format('d M Y');

                        return $type . ($date ? ' - ' . $date : '');
                    })
                    ->badge()
                    ->placeholder('No Report'),

                // Meeting Title
                TextColumn::make('title')
                    ->label('Meeting')
                    ->searchable()
                    ->placeholder('-'),

                // Meeting Date
                TextColumn::make('meeting_date')
                    ->label('Meeting Date')
                    ->date('d M Y')
                    ->sortable(),

                // Status
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'missed' => 'danger',
                        default => 'gray',
                    }),

                // Completed At
                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->placeholder('-'),

                // Reminder
                TextColumn::make('reminder_sent_at')
                    ->label('Reminder')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->placeholder('Not Sent'),

                // Created At
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Updated At
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([

                // Status Filter
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'missed' => 'Missed',
                    ]),

                // Meeting Date Filter
                \Filament\Tables\Filters\Filter::make('meeting_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From'),

                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn ($query, $date) =>
                                    $query->whereDate('meeting_date', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn ($query, $date) =>
                                    $query->whereDate('meeting_date', '<=', $date)
                            );
                    }),
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

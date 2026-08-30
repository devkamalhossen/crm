<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =========================
                // Client & Service Information
                // =========================
                Section::make('Client & Service Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('clientService.client.name')
                                    ->label('Client Name')
                                    ->placeholder('-'),

                                TextEntry::make('clientService.client.company_name')
                                    ->label('Company Name')
                                    ->placeholder('-'),

                                TextEntry::make('clientService.service_type')
                                    ->label('Service Type')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge()
                                    ->placeholder('-'),

                                TextEntry::make('clientService.payment_type')
                                    ->label('Payment Type')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge()
                                    ->placeholder('-'),

                                TextEntry::make('clientService.total_amount')
                                    ->label('Total Amount')
                                    ->money('BDT')
                                    ->placeholder('-'),

                                TextEntry::make('clientService.advance_amount')
                                    ->label('Advance Amount')
                                    ->money('BDT')
                                    ->placeholder('-'),

                                TextEntry::make('clientService.installment_months')
                                    ->label('Installment Months')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? $state . ' ' . ($state == 1 ? 'Month' : 'Months')
                                            : '-'
                                    )
                                    ->placeholder('-'),

                                TextEntry::make('clientService.status')
                                    ->label('Service Status')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge()
                                    ->placeholder('-'),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =========================
                // Sales & Project Manager
                // =========================
                Section::make('Team Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('clientService.salesTeams')
                                    ->label('Sales Person')
                                    ->formatStateUsing(function ($state, $record) {
                                        $salesTeams = $record->clientService?->salesTeams;

                                        if (! $salesTeams || $salesTeams->isEmpty()) {
                                            return '-';
                                        }

                                        return $salesTeams
                                            ->pluck('name')
                                            ->join(', ');
                                    })
                                    ->placeholder('-'),

                                TextEntry::make('projectManager.name')
                                    ->label('Project Manager')
                                    ->placeholder('Not Assigned'),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =========================
                // Project Information
                // =========================
                Section::make('Project Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('start_date')
                                    ->label('Project Start Date')
                                    ->date('d M Y')
                                    ->placeholder('-'),

                                TextEntry::make('duration_months')
                                    ->label('Project Duration')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? $state . ' ' . ($state == 1 ? 'Month' : 'Months')
                                            : '-'
                                    )
                                    ->placeholder('-'),

                                TextEntry::make('end_date')
                                    ->label('Project End Date')
                                    ->date('d M Y')
                                    ->placeholder('-'),

                                TextEntry::make('status')
                                    ->label('Project Status')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'not_started' => 'gray',
                                        'in_progress' => 'info',
                                        'completed'   => 'success',
                                        'delivered'   => 'success',
                                        'on_hold'     => 'warning',
                                        'cancelled'   => 'danger',
                                        default       => 'gray',
                                    }),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =========================
                // Service Dates
                // =========================
                Section::make('Service Timeline')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('clientService.start_date')
                                    ->label('Service Start Date')
                                    ->date('d M Y')
                                    ->placeholder('-'),

                                TextEntry::make('clientService.end_date')
                                    ->label('Service End Date')
                                    ->date('d M Y')
                                    ->placeholder('-'),

                                TextEntry::make('clientService.project_status')
                                    ->label('Service Project Status')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge()
                                    ->placeholder('-'),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =========================
                // Notes
                // =========================
                Section::make('Notes')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Project Notes')
                            ->placeholder('No notes available.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // =========================
                // Record Information
                // =========================
                Section::make('Record Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime('d M Y, h:i A')
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime('d M Y, h:i A')
                                    ->placeholder('-'),

                            ]),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}

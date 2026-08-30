<?php

namespace App\Filament\Resources\ProjectReports\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =====================================
                // Project & Client Information
                // =====================================
                Section::make('Project & Client Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('project.clientService.client.name')
                                    ->label('Client Name')
                                    ->placeholder('-'),

                                TextEntry::make('project.clientService.client.company_name')
                                    ->label('Company Name')
                                    ->placeholder('-'),

                                TextEntry::make('project.clientService.service_type')
                                    ->label('Service Type')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge()
                                    ->placeholder('-'),

                                TextEntry::make('project.projectManager.name')
                                    ->label('Project Manager')
                                    ->placeholder('Not Assigned'),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =====================================
                // Report Information
                // =====================================
                Section::make('Report Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('report_type')
                                    ->label('Report Type')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucwords(str_replace('_', ' ', $state))
                                            : '-'
                                    )
                                    ->badge(),

                                TextEntry::make('status')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($state) =>
                                        $state
                                            ? ucfirst($state)
                                            : '-'
                                    )
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'completed' => 'success',
                                        'overdue' => 'danger',
                                        default => 'gray',
                                    }),

                                TextEntry::make('report_date')
                                    ->label('Report Date')
                                    ->date('d M Y')
                                    ->placeholder('-'),

                                TextEntry::make('due_date')
                                    ->label('Due Date')
                                    ->date('d M Y')
                                    ->placeholder('-'),

                                TextEntry::make('completed_at')
                                    ->label('Completed At')
                                    ->dateTime('d M Y, h:i A')
                                    ->placeholder('Not Completed'),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =====================================
                // Report Link
                // =====================================
                Section::make('Report')
                    ->schema([
                        TextEntry::make('report_link')
                            ->label('Report Link')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->placeholder('No report link available.')
                            ->columnSpanFull(),

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes available.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                // =====================================
                // Record Information
                // =====================================
                Section::make('Record Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime('d M Y, h:i A')
                                    ->placeholder('-'),

                                TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime('d M Y, h:i A')
                                    ->placeholder('-'),

                            ]),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}

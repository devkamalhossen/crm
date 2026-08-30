<?php

namespace App\Filament\Resources\ProjectReports\Schemas;

use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =====================================
                // Report Information
                // =====================================
                Section::make('Report Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                Select::make('project_id')
                                    ->label('Project')
                                    ->options(function () {
                                        return Project::query()
                                            ->with(['clientService.client'])
                                            ->get()
                                            ->mapWithKeys(function ($project) {

                                                $client = $project->clientService?->client;

                                                $clientName = $client?->company_name
                                                    ?? $client?->name
                                                    ?? 'Unknown Client';

                                                $serviceType = $project->clientService?->service_type
                                                    ? ucwords(str_replace(
                                                        '_',
                                                        ' ',
                                                        $project->clientService->service_type
                                                    ))
                                                    : 'Unknown Service';

                                                return [
                                                    $project->id =>
                                                        "{$clientName} - {$serviceType} (Project #{$project->id})",
                                                ];
                                            })
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('report_type')
                                    ->label('Report Type')
                                    ->options([
                                        'weekly' => 'Weekly',
                                        'bi_weekly' => 'Bi-Weekly',
                                        'monthly' => 'Monthly',
                                    ])
                                    ->native(false)
                                    ->required(),

                                DatePicker::make('report_date')
                                    ->label('Report Date')
                                    ->required(),

                                DatePicker::make('due_date')
                                    ->label('Due Date')
                                    ->helperText('Deadline for completing this report.'),

                                Select::make('status')
                                    ->label('Report Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                        'overdue' => 'Overdue',
                                    ])
                                    ->default('pending')
                                    ->native(false)
                                    ->required(),

                                DateTimePicker::make('completed_at')
                                    ->label('Completed At')
                                    ->seconds(false)
                                    ->nullable(),

                            ]),
                    ])
                    ->columnSpanFull(),

                // =====================================
                // Report Link
                // =====================================
                Section::make('Report')
                    ->schema([

                        TextInput::make('report_link')
                            ->label('Report Link')
                            ->url()
                            ->placeholder('https://docs.google.com/...')
                            ->helperText(
                                'Add Google Sheet link or any external report URL.'
                            )
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4)
                            ->placeholder('Add any additional information about this report.')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

            ]);
    }
}

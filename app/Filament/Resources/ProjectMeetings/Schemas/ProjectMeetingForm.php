<?php

namespace App\Filament\Resources\ProjectMeetings\Schemas;

use App\Models\ProjectReport;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectMeetingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Meeting Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                               Select::make('project_id')
                                ->label('Project')
                                ->options(function () {
                                    return \App\Models\Project::query()
                                        ->with('clientService.client')
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
                                                    'Project #' . $project->id
                                                    . ' - '
                                                    . $clientName
                                                    . ' - '
                                                    . $serviceType,
                                            ];
                                        });
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required()
                                ->helperText('Select the project for this meeting.'),

                                Select::make('project_report_id')
                                    ->label('Project Report')
                                    ->options(function (callable $get) {
                                        $projectId = $get('project_id');

                                        if (! $projectId) {
                                            return [];
                                        }

                                        return ProjectReport::query()
                                            ->where('project_id', $projectId)
                                            ->orderByDesc('report_date')
                                            ->get()
                                            ->mapWithKeys(function ($report) {
                                                return [
                                                    $report->id =>
                                                        ucwords(str_replace('_', ' ', $report->report_type))
                                                        . ' - '
                                                        . ($report->report_date?->format('d M Y') ?? 'No Date'),
                                                ];
                                            });
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->disabled(fn (callable $get) => ! $get('project_id'))
                                    ->helperText('Select the report related to this meeting.'),

                                DatePicker::make('meeting_date')
                                    ->label('Meeting Date')
                                    ->required()
                                    ->native(false),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'scheduled' => 'Scheduled',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        'missed' => 'Missed',
                                    ])
                                    ->default('scheduled')
                                    ->required(),

                                TextInput::make('title')
                                    ->label('Meeting Title')
                                    ->maxLength(255),

                                DateTimePicker::make('completed_at')
                                    ->label('Completed At')
                                    ->native(false),

                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Meeting Details')
                    ->schema([
                        Textarea::make('agenda')
                            ->label('Agenda')
                            ->rows(4)
                            ->placeholder('Enter meeting agenda...')
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4)
                            ->placeholder('Enter meeting notes...')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

               Section::make('Reminder')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('reminder_at')
                                    ->label('Reminder At')
                                    ->native(false)
                                    ->helperText('Set when the meeting reminder should be sent.'),

                                DateTimePicker::make('reminder_sent_at')
                                    ->label('Reminder Sent At')
                                    ->native(false)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Automatically updated when the reminder is sent.'),
                            ]),
                    ])
                    ->columnSpanFull(),
                    
            ]);
    }
}

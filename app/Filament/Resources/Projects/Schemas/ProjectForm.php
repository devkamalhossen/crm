<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\ClientService;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Project Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                Select::make('client_service_id')
                                    ->label('Client Service')
                                    ->options(function () {
                                        return ClientService::query()
                                            ->with('client')
                                            ->where('status', 'active')
                                            ->get()
                                            ->mapWithKeys(function ($service) {
                                                $clientName = $service->client?->company_name
                                                    ?? $service->client?->name
                                                    ?? 'Unknown Client';

                                                $serviceName = ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $service->service_type
                                                    )
                                                );

                                                return [
                                                    $service->id =>
                                                        "{$clientName} - {$serviceName}",
                                                ];
                                            });
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('project_manager_id')
                                    ->label('Project Manager')
                                    ->options(
                                        User::query()
                                            ->where('role', 'project_manager')
                                            ->where('status', 'active')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                DatePicker::make('start_date')
                                    ->label('Project Start Date')
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $duration = $get('duration_months');

                                        if ($state && $duration) {
                                            $set(
                                                'end_date',
                                                \Carbon\Carbon::parse($state)
                                                    ->addMonths((int) $duration)
                                                    ->format('Y-m-d')
                                            );
                                        }
                                    }),

                                TextInput::make('duration_months')
                                    ->label('Project Duration (Months)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('e.g. 6')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        $startDate = $get('start_date');

                                        if ($startDate && $state) {
                                            $set(
                                                'end_date',
                                                \Carbon\Carbon::parse($startDate)
                                                    ->addMonths((int) $state)
                                                    ->format('Y-m-d')
                                            );
                                        }
                                    }),

                                DatePicker::make('end_date')
                                    ->label('Project End Date')
                                    ->native(false)
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('status')
                                    ->label('Project Status')
                                    ->options([
                                        'not_started' => 'Not Started',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'delivered' => 'Delivered',
                                        'on_hold' => 'On Hold',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('not_started')
                                    ->required(),

                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Project Notes')
                            ->rows(4)
                            ->placeholder('Add any additional project notes...')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}

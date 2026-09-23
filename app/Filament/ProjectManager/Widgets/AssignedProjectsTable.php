<?php

namespace App\Filament\ProjectManager\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AssignedProjectsTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Project::query()
                    ->where('project_manager_id', auth()->id())
                    ->with(['clientService.client']) // Relational data eager load kora
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                // Client Name (Project -> clientService -> client -> name)
                Tables\Columns\TextColumn::make('clientService.client.name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable(),

                // Company Name
                Tables\Columns\TextColumn::make('clientService.client.company_name')
                    ->label('Company Name'),

                // Service Type (Project -> clientService -> service_type)
                Tables\Columns\TextColumn::make('clientService.service_type')
                    ->label('Service Name')
                    ->badge(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'delivered' => 'info',
                        'on_hold' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
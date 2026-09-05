<?php

namespace App\Filament\ProjectManager\Widgets;

use App\Models\ProjectReport;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ReportingOverview extends TableWidget
{
    protected static ?string $heading = 'Reporting';

    protected int|string|array $columnSpan = ['md' => 1];

    protected function getTableQuery(): Builder
    {
        return ProjectReport::query()
            ->whereHas('project', fn (Builder $query) => $query->where('project_manager_id', auth()->id()))
            ->with('project')
            ->orderByRaw("CASE WHEN status = 'overdue' THEN 0 WHEN status = 'pending' THEN 1 ELSE 2 END")
            ->orderBy('due_date');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('project_id')
                ->label('Project')
                ->formatStateUsing(fn ($state): string => 'Project #' . $state),
            TextColumn::make('report_type')
                ->label('Report')
                ->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '-'),
            TextColumn::make('due_date')
                ->label('Due')
                ->date('d M Y')
                ->sortable(),
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'completed' => 'success',
                    'overdue' => 'danger',
                    default => 'warning',
                }),
            TextColumn::make('report_link')
                ->label('Report Link')
                ->formatStateUsing(fn (?string $state): string => $state ? 'Open report' : '-')
                ->url(fn ($record): ?string => $record->report_link)
                ->openUrlInNewTab(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10];
    }
}
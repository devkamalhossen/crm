<?php

namespace App\Filament\ProjectManager\Widgets;

use App\Models\ProjectMeeting;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MeetingsOverview extends TableWidget
{
    protected static ?string $heading = 'Meetings';

    protected int|string|array $columnSpan = ['md' => 1];

    protected function getTableQuery(): Builder
    {
        return ProjectMeeting::query()
            ->whereHas('project', fn (Builder $query) => $query->where('project_manager_id', auth()->id()))
            ->where('status', 'scheduled')
            ->whereDate('meeting_date', '>=', today())
            ->with('project')
            ->orderBy('meeting_date');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->label('Meeting')
                ->placeholder('Project meeting'),
            TextColumn::make('project_id')
                ->label('Project')
                ->formatStateUsing(fn ($state): string => 'Project #' . $state),
            TextColumn::make('meeting_date')
                ->label('Date')
                ->date('d M Y')
                ->badge()
                ->color(fn ($state): string => $state?->isToday() ? 'warning' : 'info'),
            TextColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state, $record): string => $record->meeting_date?->isToday() ? "Today's meeting" : 'Upcoming')
                ->badge()
                ->color(fn ($state, $record): string => $record->meeting_date?->isToday() ? 'warning' : 'info'),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10];
    }
}
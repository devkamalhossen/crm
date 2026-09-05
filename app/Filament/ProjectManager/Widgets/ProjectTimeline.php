<?php

namespace App\Filament\ProjectManager\Widgets;

use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectTimeline extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $projects = Project::query()
            ->where('project_manager_id', auth()->id());

        return [
            Stat::make('Total Projects', (clone $projects)->count())
                ->description('All assigned projects')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),
            Stat::make('In Progress', (clone $projects)->where('status', 'in_progress')->count())
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
            Stat::make('Completed', (clone $projects)->whereIn('status', ['completed', 'delivered'])->count())
                ->description('Completed or delivered')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('On Hold', (clone $projects)->where('status', 'on_hold')->count())
                ->description('Needs follow-up')
                ->descriptionIcon('heroicon-m-pause-circle')
                ->color('warning'),
            Stat::make('Upcoming / Starting', (clone $projects)
                ->whereIn('status', ['not_started'])
                ->whereDate('start_date', '>=', today())
                ->count())
                ->description('Not started yet')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('gray'),
        ];
    }
}
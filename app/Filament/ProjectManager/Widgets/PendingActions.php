<?php

namespace App\Filament\ProjectManager\Widgets;

use App\Models\Project;
use App\Models\ProjectMeeting;
use App\Models\ProjectReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PendingActions extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $managerId = auth()->id();
        $today = today();
        $nextWeek = $today->copy()->addDays(7);

        return [
            Stat::make('Pending Reports', ProjectReport::query()
                ->whereHas('project', fn ($query) => $query->where('project_manager_id', $managerId))
                ->where('status', 'pending')
                ->count())
                ->description('Reports waiting to be completed')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
            Stat::make('Upcoming Report Due', ProjectReport::query()
                ->whereHas('project', fn ($query) => $query->where('project_manager_id', $managerId))
                ->whereIn('status', ['pending', 'overdue'])
                ->whereBetween('due_date', [$today, $nextWeek])
                ->count())
                ->description('Due within the next 7 days')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
            Stat::make('Upcoming Client Meetings', ProjectMeeting::query()
                ->whereHas('project', fn ($query) => $query->where('project_manager_id', $managerId))
                ->where('status', 'scheduled')
                ->whereBetween('meeting_date', [$today, $nextWeek])
                ->count())
                ->description('Scheduled within the next 7 days')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
            Stat::make('Other Pending Project Tasks', Project::query()
                ->where('project_manager_id', $managerId)
                ->whereIn('status', ['not_started', 'on_hold'])
                ->count())
                ->description('Projects needing follow-up')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('gray'),
        ];
    }
}
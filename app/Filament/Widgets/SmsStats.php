<?php

namespace App\Filament\Widgets;

use App\Models\SmsLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SmsStats extends StatsOverviewWidget
{
    protected ?string $heading = 'SMS Summary';

    protected function getStats(): array
    {
        $totalSms = SmsLog::query()->count();
        $sentSms = SmsLog::query()->where('status', 'sent')->count();
        $failedSms = SmsLog::query()->where('status', 'failed')->count();
        $pendingSms = SmsLog::query()->where('status', 'pending')->count();

        return [
            Stat::make('Total SMS', number_format($totalSms))
                ->description('All SMS attempts')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make('Sent SMS', number_format($sentSms))
                ->description('Successfully submitted')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Failed SMS', number_format($failedSms))
                ->description('Provider or system failures')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Pending SMS', number_format($pendingSms))
                ->description('Waiting to be processed')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}

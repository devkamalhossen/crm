<?php

namespace App\Filament\Client\Widgets;

use App\Filament\Client\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentReminders extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $reminderLimit = $today->copy()->addDays(7);
        $invoices = Invoice::query()
            ->where('user_id', auth()->id())
            ->whereNotIn('status', ['cancelled', 'draft'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', $reminderLimit)
            ->get();

        $overdue = $invoices->filter(fn (Invoice $invoice): bool => $invoice->due_date->isBefore($today) && $invoice->due_amount > 0);
        $dueSoon = $invoices->filter(fn (Invoice $invoice): bool => $invoice->due_date->betweenIncluded($today, $reminderLimit) && $invoice->due_amount > 0);

        return [
            Stat::make('Overdue payments', $overdue->count())
                ->description('Invoices requiring attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->url(InvoiceResource::getUrl('index')),
            Stat::make('Due within 7 days', $dueSoon->count())
                ->description('Upcoming payment reminders')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(InvoiceResource::getUrl('index')),
            Stat::make('Outstanding balance', 'BDT ' . number_format($invoices->sum(fn (Invoice $invoice): float => $invoice->due_amount), 2))
                ->description('Across upcoming and overdue invoices')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->url(InvoiceResource::getUrl('index')),
        ];
    }
}

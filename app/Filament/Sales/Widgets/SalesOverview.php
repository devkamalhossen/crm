<?php

namespace App\Filament\Sales\Widgets;

use App\Models\ClientService;
use App\Models\Commission;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverview extends StatsOverviewWidget
{
    public ?string $period = 'month';

    public ?string $from = null;

    public ?string $until = null;

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('period')
                    ->label('Reporting period')
                    ->options([
                        'day' => 'Daily',
                        'week' => 'Weekly',
                        'month' => 'Monthly',
                        'quarter' => 'Quarterly',
                        'year' => 'Yearly',
                        'custom' => 'Custom date range',
                    ])
                    ->live(),
                DatePicker::make('from')
                    ->label('From')
                    ->visible(fn ($get): bool => $get('period') === 'custom')
                    ->live(),
                DatePicker::make('until')
                    ->label('Until')
                    ->visible(fn ($get): bool => $get('period') === 'custom')
                    ->live(),
                $this->getSectionContentComponent(),
            ]);
    }

    public function updatedPeriod(): void
    {
        $this->cachedStats = null;
    }

    public function updatedFrom(): void
    {
        $this->cachedStats = null;
    }

    public function updatedUntil(): void
    {
        $this->cachedStats = null;
    }

    protected function getStats(): array
    {
        [$start, $end, $label] = $this->dateRange();
        $salesTeamId = auth()->user()?->salesTeam?->getKey() ?? 0;

        $services = ClientService::query()
            ->whereHas('salesTeams', fn ($query) => $query->whereKey($salesTeamId))
            ->whereBetween('created_at', [$start, $end]);

        $commissions = Commission::query()
            ->where('sales_team_id', $salesTeamId)
            ->whereBetween('earned_at', [$start->toDateString(), $end->toDateString()]);

        $conversionCount = (clone $services)->count();
        $commissionAmount = (clone $commissions)->sum('commission_amount');
        $paidAmount = (clone $commissions)->where('status', 'paid')->sum('commission_amount');
        $approvedAmount = (clone $commissions)->whereIn('status', ['approved', 'paid'])->sum('commission_amount');

        return [
            Stat::make('Conversions', number_format($conversionCount))
                ->description($label)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Commission earned', '৳ '.number_format((float) $commissionAmount, 2))
                ->description('All commission statuses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
            Stat::make('Approved / paid', '৳ '.number_format((float) $approvedAmount, 2))
                ->description('Ready or already settled')
                ->color('info'),
            Stat::make('Paid to date', '৳ '.number_format((float) $paidAmount, 2))
                ->description('Completed commission payments')
                ->color('success'),
        ];
    }

    private function dateRange(): array
    {
        $now = Carbon::now();

        if ($this->period === 'custom') {
            $start = $this->from ? Carbon::parse($this->from)->startOfDay() : $now->copy()->startOfMonth();
            $end = $this->until ? Carbon::parse($this->until)->endOfDay() : $now->copy()->endOfDay();

            return [$start, $end, 'Custom date range'];
        }

        $start = match ($this->period) {
            'day' => $now->copy()->startOfDay(),
            'week' => $now->copy()->startOfWeek(),
            'quarter' => $now->copy()->startOfQuarter(),
            'year' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfMonth(),
        };
        $end = match ($this->period) {
            'day' => $now->copy()->endOfDay(),
            'week' => $now->copy()->endOfWeek(),
            'quarter' => $now->copy()->endOfQuarter(),
            'year' => $now->copy()->endOfYear(),
            default => $now->copy()->endOfMonth(),
        };

        return [$start, $end, ucfirst($this->period ?: 'month').' report'];
    }
}

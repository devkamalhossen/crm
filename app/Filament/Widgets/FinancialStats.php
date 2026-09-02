<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinancialStats extends StatsOverviewWidget
{
    public ?string $filter = 'month';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('filter')
                    ->label('Period')
                    ->options($this->getFilterOptions())
                    ->default('month')
                    ->live(),
                $this->getSectionContentComponent(),
            ]);
    }

    protected function getFilterOptions(): array
    {
        return [
            'month' => 'This Month',
            'quarter' => 'This Quarter',
            'year' => 'This Year',
        ];
    }

    public function updatedFilter(): void
    {
        $this->cachedStats = null;
    }

    protected function getStats(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Determine Period
        |--------------------------------------------------------------------------
        */

        $now = Carbon::now();

        switch ($this->filter) {
            case 'quarter':
                $startDate = $now->copy()->startOfQuarter();
                $endDate = $now->copy()->endOfQuarter();
                $periodLabel = 'This quarter';

                break;

            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'This year';

                break;

            case 'month':
            default:
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = 'This month';

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Total Income
        |--------------------------------------------------------------------------
        */

        $totalIncome = Payment::query()
            ->where('status', 'completed')
            ->whereBetween('payment_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Total Expenses
        |--------------------------------------------------------------------------
        */

        $totalExpenses = Expense::query()
            ->whereBetween('expense_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Net Profit
        |--------------------------------------------------------------------------
        */

        $netProfit = $totalIncome - $totalExpenses;

        /*
        |--------------------------------------------------------------------------
        | Outstanding Due
        |--------------------------------------------------------------------------
        */

        $outstandingDue = Invoice::query()
            ->whereNotIn('status', ['cancelled', 'draft'])
            ->withSum([
                'payments as paid_amount' => function ($query) {
                    $query->where('status', 'completed');
                },
            ], 'amount')
            ->get()
            ->sum(function ($invoice) {
                return max(
                    0,
                    (float) $invoice->total_amount
                    - (float) ($invoice->paid_amount ?? 0)
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */

        return [

            Stat::make(
                'Total Income',
                '৳ ' . number_format((float) $totalIncome, 2)
            )
                ->description("Completed payments — {$periodLabel}")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make(
                'Total Expenses',
                '৳ ' . number_format((float) $totalExpenses, 2)
            )
                ->description("Expenses — {$periodLabel}")
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make(
                'Net Profit',
                '৳ ' . number_format((float) $netProfit, 2)
            )
                ->description("Income minus expenses — {$periodLabel}")
                ->descriptionIcon(
                    $netProfit >= 0
                        ? 'heroicon-m-banknotes'
                        : 'heroicon-m-exclamation-triangle'
                )
                ->color(
                    $netProfit >= 0
                        ? 'success'
                        : 'danger'
                ),

            Stat::make(
                'Outstanding Due',
                '৳ ' . number_format((float) $outstandingDue, 2)
            )
                ->description('Total unpaid invoice amount')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
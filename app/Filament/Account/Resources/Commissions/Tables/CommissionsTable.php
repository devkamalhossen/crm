<?php

namespace App\Filament\Account\Resources\Commissions\Tables;

use App\Models\Commission;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('salesTeam.name')
                    ->label('Salesperson')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clientService.client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('clientService.service_type')
                    ->label('Conversion')
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : '-')
                    ->badge(),
                TextColumn::make('commission_amount')
                    ->label('Commission')
                    ->money('BDT')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'approved' => 'info',
                        'cancelled' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('earned_at')
                    ->label('Earned')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->label('Paid')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('sales_team_id')
                    ->label('Salesperson')
                    ->relationship('salesTeam', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('earned_at')
                    ->schema([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'] ?? null, fn ($query, $date) => $query->whereDate('earned_at', '>=', $date))
                        ->when($data['until'] ?? null, fn ($query, $date) => $query->whereDate('earned_at', '<=', $date))),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View report')
                    ->modalHeading('Commission report')
                    ->modalWidth('lg'),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Commission $record): bool => $record->status === 'pending')
                    ->action(fn (Commission $record) => $record->update([
                        'status' => 'approved',
                    ])),
                Action::make('markPaid')
                    ->label('Mark paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Commission $record): bool => $record->status === 'approved')
                    ->action(fn (Commission $record) => $record->update([
                        'status' => 'paid',
                        'paid_at' => now()->toDateString(),
                    ])),
            ])
            ->recordActionsColumnLabel('Action');
    }
}

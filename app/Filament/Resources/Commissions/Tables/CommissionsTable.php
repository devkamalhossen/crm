<?php

namespace App\Filament\Resources\Commissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
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
                    ->label('Service')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                        'custom' => 'Custom Service',
                        default => $state ? ucfirst($state) : '-',
                    })
                    ->badge()
                    ->sortable(),

                TextColumn::make('commissionRule.commission_amount')
                    ->label('Commission Rule')
                    ->formatStateUsing(
                        fn ($state) => $state !== null
                            ? '৳ ' . number_format((float) $state, 2)
                            : '-'
                    )
                    ->sortable(),

                TextColumn::make('commission_amount')
                    ->label('Commission Amount')
                    ->formatStateUsing(
                        fn ($state) => '৳ ' . number_format((float) $state, 2)
                    )
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('earned_at')
                    ->label('Earned Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('paid_at')
                    ->label('Paid Date')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Commission Status')
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

                SelectFilter::make('clientService.service_type')
                    ->label('Service Type')
                    ->options([
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                        'custom' => 'Custom Service',
                    ]),

                Filter::make('earned_at')
                    ->schema([
                        DatePicker::make('earned_from')
                            ->label('Earned From'),

                        DatePicker::make('earned_until')
                            ->label('Earned Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['earned_from'] ?? null,
                                fn ($query, $date) => $query->whereDate('earned_at', '>=', $date)
                            )
                            ->when(
                                $data['earned_until'] ?? null,
                                fn ($query, $date) => $query->whereDate('earned_at', '<=', $date)
                            );
                    }),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])->recordActionsColumnLabel('Action')

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
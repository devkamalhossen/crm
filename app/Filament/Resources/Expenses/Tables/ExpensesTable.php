<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Expense')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(
                        fn ($state) => '৳ ' . number_format((float) $state, 2)
                    )
                    ->sortable(),

                TextColumn::make('expense_date')
                    ->label('Expense Date')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->tooltip(fn ($state) => $state)
                    ->toggleable(),

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
                Filter::make('expense_date')
                    ->label('Expense Date')
                    ->schema([
                        DatePicker::make('from')
                            ->label('From'),

                        DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn ($query, $date) =>
                                    $query->whereDate('expense_date', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn ($query, $date) =>
                                    $query->whereDate('expense_date', '<=', $date)
                            );
                    }),

                SelectFilter::make('category')
                    ->label('Category')
                    ->options(fn () => \App\Models\Expense::query()
                        ->whereNotNull('category')
                        ->where('category', '!=', '')
                        ->distinct()
                        ->orderBy('category')
                        ->pluck('category', 'category')
                        ->toArray()
                    ),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])->recordActionsColumnLabel('Action')

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
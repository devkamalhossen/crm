<?php

namespace App\Filament\Resources\CommissionRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommissionRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_type')
                    ->label('Service Type')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                        'custom' => 'Custom Service',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('commission_type')
                    ->label('Commission Type')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fixed' => 'Fixed Amount',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->sortable(),

                TextColumn::make('commission_amount')
                    ->label('Commission Amount')
                    ->prefix('৳ ')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
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
                SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                        'custom' => 'Custom Service',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
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
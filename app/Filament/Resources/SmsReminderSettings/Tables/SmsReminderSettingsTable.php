<?php

namespace App\Filament\Resources\SmsReminderSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SmsReminderSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Reminder')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trigger_type')
                    ->label('Trigger')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'before_due' => 'Before Due Date',
                        'after_due' => 'After Due Date',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'before_due' => 'warning',
                        'after_due' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('days')
                    ->label('Days')
                    ->formatStateUsing(fn ($state) => $state . ' ' . ($state == 1 ? 'day' : 'days'))
                    ->sortable(),

                TextColumn::make('message')
                    ->label('SMS Message')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->message)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
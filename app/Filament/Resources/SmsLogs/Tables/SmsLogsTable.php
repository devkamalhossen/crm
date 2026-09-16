<?php

namespace App\Filament\Resources\SmsLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SmsLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice.invoice_number')
                    ->label('Invoice')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('invoice.client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Phone number copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('trigger_type')
                    ->label('Trigger')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'before_due' => 'Before Due',
                        'after_due' => 'After Due',
                        'manual' => 'Manual',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'before_due' => 'warning',
                        'after_due' => 'danger',
                        'manual' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('reminderSetting.name')
                    ->label('Reminder')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->tooltip(fn ($state) => $state)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('sent_at')
                    ->label('Sent At')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->placeholder('-'),

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
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),

                SelectFilter::make('trigger_type')
                    ->label('Trigger Type')
                    ->options([
                        'before_due' => 'Before Due',
                        'after_due' => 'After Due',
                        'manual' => 'Manual',
                    ]),

                SelectFilter::make('reminder_setting_id')
                    ->label('Reminder')
                    ->relationship('reminderSetting', 'name')
                    ->searchable()
                    ->preload(),
            ])

            ->recordActions([
                ViewAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
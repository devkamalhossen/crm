<?php

namespace App\Filament\Sales\Resources\ClientServices\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.company_name')
                    ->label('Company')
                    ->searchable(),
                TextColumn::make('service_type')
                    ->label('Conversion')
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : '-')
                    ->badge()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Deal value')
                    ->money('BDT')
                    ->sortable(),
                TextColumn::make('project_status')
                    ->label('Progress')
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : '-')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Converted')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_type')
                    ->options([
                        'seo' => 'SEO',
                        'website' => 'Website',
                        'digital_marketing' => 'Digital marketing',
                    ]),
                SelectFilter::make('project_status')
                    ->options([
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View conversion')
                    ->modalHeading('Conversion report')
                    ->modalWidth('xl'),
            ]);
    }
}
<?php

namespace App\Filament\Resources\CommissionRules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommissionRuleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Commission Rule Details')
                    ->schema([
                        TextEntry::make('service_type')
                            ->label('Service Type')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'seo' => 'SEO',
                                'website' => 'Website Development',
                                'digital_marketing' => 'Digital Marketing',
                                'custom' => 'Custom Service',
                                default => ucfirst($state),
                            })
                            ->badge(),

                        TextEntry::make('commission_type')
                            ->label('Commission Type')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'fixed' => 'Fixed Amount',
                                default => ucfirst($state),
                            })
                            ->badge(),

                        TextEntry::make('commission_amount')
                            ->label('Commission Amount')
                            ->prefix('৳ ')
                            ->numeric(decimalPlaces: 2),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Record Information')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, h:i A')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d M Y, h:i A')
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
<?php

namespace App\Filament\Resources\Commissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Commission Details')
                    ->schema([
                        TextEntry::make('salesTeam.name')
                            ->label('Salesperson')
                            ->placeholder('-'),

                        TextEntry::make('clientService.client.name')
                            ->label('Client')
                            ->placeholder('-'),

                        TextEntry::make('clientService.service_type')
                            ->label('Service Type')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'seo' => 'SEO',
                                'website' => 'Website Development',
                                'digital_marketing' => 'Digital Marketing',
                                'custom' => 'Custom Service',
                                default => $state ? ucfirst($state) : '-',
                            })
                            ->badge()
                            ->placeholder('-'),

                        TextEntry::make('commissionRule.commission_amount')
                            ->label('Commission Rule')
                            ->formatStateUsing(
                                fn ($state) => $state !== null
                                    ? '৳ ' . number_format((float) $state, 2)
                                    : '-'
                            )
                            ->placeholder('-'),

                        TextEntry::make('commission_amount')
                            ->label('Commission Amount')
                            ->formatStateUsing(
                                fn ($state) => '৳ ' . number_format((float) $state, 2)
                            )
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Commission Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'info',
                                'paid' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('earned_at')
                            ->label('Earned Date')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('paid_at')
                            ->label('Paid Date')
                            ->date('d M Y')
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make('Notes')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('')
                            ->placeholder('No notes available.')
                            ->columnSpanFull(),
                    ]),

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
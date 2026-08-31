<?php

namespace App\Filament\Resources\CommissionRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommissionRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Commission Rule')
                    ->schema([
                        Select::make('service_type')
                            ->label('Service Type')
                            ->options([
                                'seo' => 'SEO',
                                'website' => 'Website Development',
                                'digital_marketing' => 'Digital Marketing',
                                'custom' => 'Custom Service',
                            ])
                            ->native(false)
                            ->required(),

                        Select::make('commission_type')
                            ->label('Commission Type')
                            ->options([
                                'fixed' => 'Fixed Amount',
                            ])
                            ->default('fixed')
                            ->native(false)
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        TextInput::make('commission_amount')
                            ->label('Commission Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0)
                            ->step(0.01)
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->native(false)
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Optional notes about this commission rule...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
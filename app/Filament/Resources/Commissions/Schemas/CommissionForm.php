<?php

namespace App\Filament\Resources\Commissions\Schemas;

use App\Models\ClientService;
use App\Models\CommissionRule;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Commission Details')
                    ->description('Assign a commission to a salesperson based on a converted client service.')
                    ->schema([
                        Select::make('sales_team_id')
                            ->label('Salesperson')
                            ->relationship('salesTeam', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select the salesperson who converted the client.'),

                        Select::make('client_service_id')
                            ->label('Client Service')
                            ->options(function () {
                                return ClientService::with('client')
                                    ->get()
                                    ->mapWithKeys(function (ClientService $service) {
                                        $clientName = $service->client?->name ?? 'Unknown Client';

                                        $serviceType = match ($service->service_type) {
                                            'seo' => 'SEO',
                                            'website' => 'Website Development',
                                            'digital_marketing' => 'Digital Marketing',
                                            default => ucfirst($service->service_type),
                                        };

                                        return [
                                            $service->id => "{$clientName} — {$serviceType}",
                                        ];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select the client service that generated this commission.'),

                        Select::make('commission_rule_id')
                            ->label('Commission Rule')
                            ->options(function () {
                                return CommissionRule::query()
                                    ->where('status', 'active')
                                    ->get()
                                    ->mapWithKeys(function (CommissionRule $rule) {
                                        $serviceType = match ($rule->service_type) {
                                            'seo' => 'SEO',
                                            'website' => 'Website Development',
                                            'digital_marketing' => 'Digital Marketing',
                                            'custom' => 'Custom Service',
                                            default => ucfirst($rule->service_type),
                                        };

                                        return [
                                            $rule->id => "{$serviceType} — ৳ " .
                                                number_format($rule->commission_amount, 2),
                                        ];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select the active commission rule.'),

                        TextInput::make('commission_amount')
                            ->label('Commission Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->minValue(0)
                            ->step(0.01)
                            ->required()
                            ->helperText('Commission amount earned by the salesperson.'),

                        Select::make('status')
                            ->label('Commission Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->native(false)
                            ->required(),

                        DatePicker::make('earned_at')
                            ->label('Earned Date')
                            ->default(now())
                            ->native(false),

                        DatePicker::make('paid_at')
                            ->label('Paid Date')
                            ->native(false)
                            ->visible(fn ($get) => $get('status') === 'paid'),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Add any additional notes...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
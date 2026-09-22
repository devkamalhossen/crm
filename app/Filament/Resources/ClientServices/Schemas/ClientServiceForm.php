<?php

namespace App\Filament\Resources\ClientServices\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class ClientServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Client')
                    ->relationship(
                        name: 'client',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query
                            ->where('role', 'client')
                            ->where('status', 'active')
                            ->orderBy('name')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} — {$record->email} — {$record->phone} — {$record->company_name}")
                    ->searchable(['name', 'email', 'phone', 'company_name'])
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->regex('/^[\pL\s\-\.]+$/u')
                            ->validationAttribute('Name'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique('users', 'email')
                            ->maxLength(255)
                            ->regex('/^[\w\._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/')
                            ->validationAttribute('Email Address'),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->maxLength(255),

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->default('12345678'),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $data['role'] = 'client';
                        $data['status'] = 'active';

                        $user = User::create($data);
                        return $user->id;
                    })
                    ->editOptionForm([
                        TextInput::make('name')->required()->maxLength(255)
                            ->regex('/^[\pL\s\-\.]+$/u')
                            ->validationAttribute('Name'),
                        TextInput::make('email')->email()->required()->maxLength(255)
                            ->regex('/^[\w\._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/')
                            ->validationAttribute('Email Address'),
                        TextInput::make('phone')->tel()->maxLength(20),
                        TextInput::make('company_name')->label('Company Name')->maxLength(255),
                    ]),

                Select::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'seo' => 'SEO',
                        'website' => 'Website Development',
                        'digital_marketing' => 'Digital Marketing',
                    ])
                    ->required(),

                Select::make('payment_type')
                    ->label('Payment Type')
                    ->options([
                        'monthly' => 'Monthly',
                        'project_based' => 'Project Based',
                        'yearly' => 'Yearly',
                    ])
                    ->required(),

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('৳')
                    ->required(),

                TextInput::make('advance_amount')
                    ->label('Advance Amount')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('৳')
                    ->default(0),

                TextInput::make('installment_months')
                    ->label('Installment Months')
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->maxValue(120)
                    ->nullable(),

                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->native(false),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->native(false)
                    ->afterOrEqual('start_date'),

                // Select::make('project_status')
                //     ->label('Project Status')
                //     ->options([
                //         'in_progress' => 'In Progress',
                //         'completed' => 'Completed',
                //         'delivered' => 'Delivered',
                //     ])
                //     ->visible(fn ($get) => in_array(
                //         $get('service_type'),
                //         ['website', 'digital_marketing']
                //     )),

                Select::make('project_status')
                    ->label('Project Status')
                    ->options([
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                    ])
                    ->default('in_progress')
                    ->nullable(),

                Select::make('status')
                    ->label('Service Status')
                    ->options([
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('active')
                    ->required(),

                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(4)
                    ->columnSpanFull(),
            
            ]);
    }
}

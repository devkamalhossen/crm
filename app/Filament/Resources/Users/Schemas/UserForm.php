<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'account' => 'Account',
                        'client' => 'Client',
                        'project_manager' => 'Project manager',
                        'sales' => 'Sales',
                    ])
                    ->default('client')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('company_name')
                    ->default(null),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive', 'pending' => 'Pending'])
                    ->default('active')
                    ->required(),
                DateTimePicker::make('last_login_at'),
                TextInput::make('last_login_ip')
                    ->default(null),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}

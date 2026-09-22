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
                    ->required()
                    ->disabled(fn () => auth()->user()?->role !== 'admin')
                    ->dehydrated(true),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->regex('/^[\pL\s\-\.]+$/u')
                    ->validationAttribute('Name'),

               TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(table: 'users', ignoreRecord: true)
                    ->maxLength(255)
                    ->regex('/^[\w\._%+-]+@[\w.-]+\.[a-zA-Z]{2,}$/')
                    ->validationAttribute('Email Address'),

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

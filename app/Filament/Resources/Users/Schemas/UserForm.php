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
                    ->minLength(2)
                    ->maxLength(255)
                    ->trim()
                    ->regex('/^(?=.*[\pL])[\pL]+(?:[\pL\s.-]*[\pL])?$/u')
                    ->validationMessages([
                        'required' => 'Name is required.',
                        'minLength' => 'Name must be at least 2 characters.',
                        'regex' => 'Please enter a valid name. Name cannot contain only symbols.',
                    ])
                    ->validationAttribute('Name'),

               TextInput::make('email')
                    ->label('Email address')
                    ->required()
                    ->email()
                    ->unique(table: 'users', ignoreRecord: true)
                    ->maxLength(255)
                    ->trim()
                    ->regex('/^[A-Za-z0-9]+(?:[._%+-]*[A-Za-z0-9]+)*@gmail\.com$/')
                    ->validationMessages([
                        'required' => 'Email address is required.',
                        'email' => 'Please enter a valid Gmail address.',
                        'regex' => 'Please enter a valid Gmail address ending with @gmail.com.',
                        'unique' => 'This email address is already registered.',
                    ])
                    ->validationAttribute('Email Address'),

                TextInput::make('phone')
                    ->tel()
                    ->default(null),

                TextInput::make('company_name')
                    ->default(null)
                    ->trim()
                    ->minLength(2)
                    ->maxLength(255)
                    ->regex('/^(?=.*[\pL\pN])[\pL\pN\s&.,()\'-]+$/u')
                    ->validationMessages([
                        'minLength' => 'Company name must be at least 2 characters.',
                        'regex' => 'Please enter a valid company name.',
                    ])
                    ->validationAttribute('Company Name'),

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->disabledOn('edit')
                    ->dehydrated(fn (string $operation): bool => $operation === 'create'),

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

<?php

namespace App\Filament\Resources\SalesTeams\Pages;

use App\Filament\Resources\SalesTeams\SalesTeamResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesTeam extends CreateRecord
{
    protected static string $resource = SalesTeamResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'role' => 'sales',
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['mobile_number'] ?? null,
            'password' => $data['password'],
            'status' => $data['status'] ?? 'active',
        ]);

        $data['user_id'] = $user->id;

        unset($data['role']);
        unset($data['password']);

        return $data;
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'phone' => '01710000001',
            'company_name' => 'Intellec IT',
            'password' => '12345678',
            'status' => 'active',
        ]);

        User::create([
            'role' => 'account',
            'name' => 'Account Manager',
            'email' => 'account@gmail.com',
            'phone' => '01710000002',
            'company_name' => 'Intellec IT',
            'password' => '12345678',
            'status' => 'active',
        ]);

        User::create([
            'role' => 'project_manager',
            'name' => 'Project Manager',
            'email' => 'pm@gmail.com',
            'phone' => '01710000003',
            'company_name' => 'Intellec IT',
            'password' => '12345678',
            'status' => 'active',
        ]);

        User::create([
            'role' => 'sales',
            'name' => 'Sales Manager',
            'email' => 'sales@gmail.com',
            'phone' => '01710000004',
            'company_name' => 'Intellec IT',
            'password' => '12345678',
            'status' => 'active',
        ]);

        User::create([
            'role' => 'client',
            'name' => 'Demo Client',
            'email' => 'client@example.com',
            'phone' => '01710000005',
            'company_name' => 'Demo Company',
            'password' => '12345678',
            'status' => 'active',
        ]);
    }
}

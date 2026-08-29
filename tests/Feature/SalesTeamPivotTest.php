<?php

namespace Tests\Feature;

use App\Filament\Resources\SalesTeams\Pages\CreateSalesTeam;
use App\Models\ClientService;
use App\Models\SalesTeam;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalesTeamPivotTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_team_can_attach_client_services(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        $serviceOne = ClientService::create([
            'user_id' => $client->id,
            'sales_team_id' => null,
            'service_type' => 'seo',
            'payment_type' => 'monthly',
            'total_amount' => 1500.00,
            'advance_amount' => 500.00,
            'installment_months' => 6,
            'start_date' => '2026-08-01',
            'end_date' => '2026-12-31',
            'project_status' => 'in_progress',
            'status' => 'active',
        ]);

        $serviceTwo = ClientService::create([
            'user_id' => $client->id,
            'sales_team_id' => null,
            'service_type' => 'website',
            'payment_type' => 'project_based',
            'total_amount' => 2500.00,
            'advance_amount' => 750.00,
            'installment_months' => 12,
            'start_date' => '2026-08-01',
            'end_date' => '2027-06-30',
            'project_status' => 'in_progress',
            'status' => 'active',
        ]);

        Livewire::test(CreateSalesTeam::class)
            ->set('data', [
                'employee_id' => 'EMP-101',
                'name' => 'Rahim Uddin',
                'designation' => 'Senior Sales Executive',
                'mobile_number' => '01712345678',
                'email' => 'rahim@example.com',
                'joining_date' => '2026-01-15',
                'status' => 'active',
                'clientServices' => [$serviceOne->id, $serviceTwo->id],
            ])
            ->call('create');

        $salesTeam = SalesTeam::query()->firstOrFail();

        $this->assertEqualsCanonicalizing(
            [$serviceOne->id, $serviceTwo->id],
            $salesTeam->clientServices()->pluck('client_services.id')->all()
        );
    }
}

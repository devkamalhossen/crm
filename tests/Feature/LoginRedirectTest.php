<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_is_sent_to_their_dashboard_when_visiting_login(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/admin');
    }

    public function test_authenticated_sales_user_is_sent_to_the_sales_dashboard_when_visiting_login(): void
    {
        $user = User::factory()->create([
            'role' => 'sales',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/sales');
    }

    public function test_authenticated_project_manager_user_is_sent_to_the_project_manager_dashboard_when_visiting_login(): void
    {
        $user = User::factory()->create([
            'role' => 'project_manager',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/project_manager');
    }
}

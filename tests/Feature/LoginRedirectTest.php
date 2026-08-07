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
}

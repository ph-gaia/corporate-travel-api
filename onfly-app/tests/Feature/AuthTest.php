<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_a_user_to_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Paulo Gaia',
            'email' => 'paulo.gaia@challenge.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user', 'token']);
    }

    /** @test */
    public function it_allows_a_user_to_login_and_get_a_jwt_token()
    {
        $user = User::factory()->create([
            'email' => 'paulo.gaia@challenge.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'paulo.gaia@challenge.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /** @test */
    public function it_rejects_invalid_login_attempts()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@challenge.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_access_protected_routes()
    {
        $user = User::factory()->create();

        $token = auth()->attempt(['email' => $user->email, 'password' => 'password']);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/protected-route');

        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_users_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/protected-route');
        $response->assertStatus(401);
    }
}

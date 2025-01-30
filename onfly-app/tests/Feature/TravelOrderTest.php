<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_travel_order()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $data = [
            'destination' => 'Belo Horizonte',
            'departure_date' => '2025-02-15',
            'return_date' => '2025-02-20',
        ];

        $response = $this->postJson('/api/travel-orders', $data);

        $response->assertStatus(201)
            ->assertJson([
                'destination' => 'Belo Horizonte',
                'status' => 'requested',
            ]);

        $this->assertDatabaseHas('travel_orders', [
            'destination' => 'Belo Horizonte',
            'user_id' => $user->id,
            'status' => 'requested',
        ]);
    }
}

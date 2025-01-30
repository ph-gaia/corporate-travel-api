<?php

namespace Tests\Feature;

use App\Models\TravelOrder;
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

    public function test_admin_can_update_travel_order_status()
    {
        $user = User::factory()->create();

        $admin = User::factory()->create([
            'is_admin' => true
        ]);

        $travelOrder = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status' => 'requested'
        ]);

        $this->actingAs($admin, 'api');

        $response = $this->patchJson("/api/travel-orders/{$travelOrder->id}/status", [
            'status' => 'approved'
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'approved']);

        $this->assertDatabaseHas('travel_orders', [
            'id' => $travelOrder->id,
            'status' => 'approved'
        ]);
    }

    public function test_user_cannot_update_own_travel_order_status()
    {
        $user = User::factory()->create([
            'is_admin' => false
        ]);

        $travelOrder = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status' => 'requested'
        ]);

        $this->actingAs($user, 'api');

        $response = $this->patchJson("/api/travel-orders/{$travelOrder->id}/status", [
            'status' => 'approved'
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('travel_orders', [
            'id' => $travelOrder->id,
            'status' => 'requested'
        ]);
    }
}

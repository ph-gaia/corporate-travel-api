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

    public function test_user_can_only_see_their_own_travel_orders()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        TravelOrder::factory()->count(3)->create(['user_id' => $user->id]);
        TravelOrder::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $this->actingAs($user, 'api')
            ->getJson('/api/travel-orders')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_admin_can_see_all_travel_orders()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        TravelOrder::factory()->count(5)->create();

        $this->actingAs($admin, 'api')
            ->getJson('/api/travel-orders')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_user_can_filter_travel_orders_by_status()
    {
        $user = User::factory()->create();
        TravelOrder::factory()->create(['user_id' => $user->id, 'status' => 'requested']);
        TravelOrder::factory()->create(['user_id' => $user->id, 'status' => 'approved']);

        $this->actingAs($user, 'api')
            ->getJson('/api/travel-orders?status=requested')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_filter_travel_orders_by_date_range()
    {
        $user = User::factory()->create();
        TravelOrder::factory()->create([
            'user_id' => $user->id,
            'departure_date' => '2025-01-01',
            'return_date' => '2025-01-10'
        ]);
        TravelOrder::factory()->create([
            'user_id' => $user->id,
            'departure_date' => '2025-02-01',
            'return_date' => '2025-02-10'
        ]);

        $this->actingAs($user, 'api')
            ->getJson('/api/travel-orders?from=2025-01-01&to=2025-01-31')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_filter_travel_orders_by_destination()
    {
        $user = User::factory()->create();
        TravelOrder::factory()->create(['user_id' => $user->id, 'destination' => 'Manaus']);
        TravelOrder::factory()->create(['user_id' => $user->id, 'destination' => 'Fortaleza']);

        $this->actingAs($user, 'api')
            ->getJson('/api/travel-orders?destination=Fortaleza')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_view_their_own_travel_order()
    {
        $user = User::factory()->create();
        $order = TravelOrder::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api')
            ->getJson("/api/travel-orders/{$order->id}")
            ->assertStatus(200)
            ->assertJson([
                'id' => $order->id,
                'destination' => $order->destination,
                'departure_date' => $order->departure_date,
                'return_date' => $order->return_date,
                'status' => $order->status,
            ]);
    }

    public function test_user_cannot_view_other_users_travel_order()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = TravelOrder::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user, 'api')
            ->getJson("/api/travel-orders/{$order->id}")
            ->assertStatus(404);
    }

    public function test_admin_can_view_any_travel_order()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $order = TravelOrder::factory()->create();

        $this->actingAs($admin, 'api')
            ->getJson("/api/travel-orders/{$order->id}")
            ->assertStatus(200)
            ->assertJson(['id' => $order->id]);
    }

    public function test_it_returns_404_if_travel_order_does_not_exist()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api')
            ->getJson('/api/travel-orders/99999')
            ->assertStatus(404);
    }
}

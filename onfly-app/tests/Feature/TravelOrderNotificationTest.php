<?php

namespace Tests\Feature;

use App\Jobs\SendTravelOrderNotification;
use App\Mail\TravelOrderNotificationMail;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TravelOrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_an_email_when_travel_order_is_approved()
    {
        Mail::fake();
        Queue::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $order = TravelOrder::factory()->create([
            'user_id' => $user->id,
            'status' => 'requested'
        ]);

        $this->actingAs($admin, 'api')
            ->patchJson("/api/travel-orders/{$order->id}/status", ['status' => 'approved'])
            ->assertStatus(200);

        Queue::assertPushed(SendTravelOrderNotification::class, function ($job) use ($order) {
            return $job->order->id === $order->id;
        });

        Mail::assertNothingSent();
    }
}

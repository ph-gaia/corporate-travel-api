<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TravelOrderService;
use App\Models\TravelOrder;
use App\Models\User;
use App\Repositories\TravelOrderInterface;
use App\Repositories\TravelOrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Mockery;
use Illuminate\Support\Facades\Auth;

class TravelOrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_travel_order_successfully()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $travelOrderRepositoryMock = Mockery::mock(TravelOrderInterface::class);

        $travelOrderData = [
            'user_id' => $user->id,
            'destination' => 'Bahia',
            'departure_date' => '2025-03-01',
            'return_date' => '2025-03-10',
            'status' => 'requested',
        ];

        $travelOrderRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($travelOrderData)
            ->andReturn(new TravelOrder($travelOrderData));

        $this->app->instance(TravelOrderInterface::class, $travelOrderRepositoryMock);

        $service = $this->app->make(TravelOrderService::class);
        $travelOrder = $service->create($travelOrderData);

        $this->assertInstanceOf(TravelOrder::class, $travelOrder);
        $this->assertEquals('Bahia', $travelOrder->destination);
        $this->assertEquals('requested', $travelOrder->status);
    }

    public function test_create_travel_order_fails_when_return_date_is_invalid()
    {
        $respository = new TravelOrderRepository();
        $service = new TravelOrderService($respository);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The return date must be after the departure date.");

        $service->create([
            'destination' => 'Curitiba',
            'departure_date' => '2025-02-10',
            'return_date' => '2025-02-09',
        ]);
    }

    public function test_list_travel_orders_with_filters()
    {
        $user = \App\Models\User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('check')->andReturn(true);

        $request = new Request([
            'status' => 'approved',
            'destination' => 'Rio de Janeiro',
            'from' => '2024-01-01',
            'to' => '2024-01-31',
        ]);

        $travelOrders = [
            new TravelOrder(['destination' => 'Rio de Janeiro', 'status' => 'approved', 'departure_date' => '2024-01-15', 'user_id' => $user->id]),
            new TravelOrder(['destination' => 'Rio de Janeiro', 'status' => 'approved', 'departure_date' => '2024-01-20', 'user_id' => $user->id]),
        ];

        $travelOrderRepositoryMock = Mockery::mock(TravelOrderRepository::class);
        $travelOrderRepositoryMock
            ->shouldReceive('listTravelOrders')
            ->once()
            ->with($request)
            ->andReturn($travelOrders);

        $this->app->instance(TravelOrderInterface::class, $travelOrderRepositoryMock);

        $service = $this->app->make(TravelOrderService::class);
        $result = $service->listTravelOrders($request);

        $this->assertEquals($travelOrders, $result);
        foreach ($result as $travelOrder) {
            $this->assertEquals('Rio de Janeiro', $travelOrder->destination);
            $this->assertEquals('approved', $travelOrder->status);
            $this->assertTrue($travelOrder->departure_date >= '2024-01-01');
            $this->assertTrue($travelOrder->departure_date <= '2024-01-31');
            $this->assertEquals($user->id, $travelOrder->user_id);
        }
    }


    public function test_list_travel_orders_without_filters()
    {
        $user = \App\Models\User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('check')->andReturn(true);

        $request = new Request();
        $travelOrders = [
            new TravelOrder(['destination' => 'Salvador', 'status' => 'requested', 'departure_date' => '2023-12-15', 'user_id' => $user->id]),
            new TravelOrder(['destination' => 'São Paulo', 'status' => 'approved', 'departure_date' => '2024-02-20', 'user_id' => $user->id]),
        ];

        $travelOrderRepositoryMock = Mockery::mock(TravelOrderRepository::class);
        $travelOrderRepositoryMock
            ->shouldReceive('listTravelOrders')
            ->once()
            ->with($request)
            ->andReturn($travelOrders);

        $this->app->instance(TravelOrderInterface::class, $travelOrderRepositoryMock);

        $service = $this->app->make(TravelOrderService::class);

        $result = $service->listTravelOrders($request);
        $this->assertEquals($travelOrders, $result);
    }
}

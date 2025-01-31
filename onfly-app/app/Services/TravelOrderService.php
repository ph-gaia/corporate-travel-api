<?php

namespace App\Services;

use App\Jobs\SendTravelOrderNotification;
use App\Models\TravelOrder;
use App\Repositories\TravelOrderInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class TravelOrderService
{
    protected $travelOrderRepository;

    public function __construct(TravelOrderInterface $travelOrderRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
    }

    public function listTravelOrders($request)
    {
        return $this->travelOrderRepository->listTravelOrders($request);
    }

    public function getTravelOrderById(int $id)
    {
        $order = $this->travelOrderRepository->findById($id);

        if (!$order) {
            throw new ModelNotFoundException();
        }

        return $order;
    }

    public function create(array $data)
    {
        return $this->travelOrderRepository->create([
            'user_id' => Auth::id(),
            'destination' => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date' => $data['return_date'],
            'status' => 'requested',
        ]);
    }

    public function update($status, TravelOrder $travelOrder)
    {
        $travelOrder->update(['status' => $status]);

        SendTravelOrderNotification::dispatch($travelOrder);

        return $travelOrder;
    }
}

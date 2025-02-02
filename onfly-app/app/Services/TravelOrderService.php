<?php

namespace App\Services;

use App\Jobs\SendTravelOrderNotification;
use App\Models\TravelOrder;
use App\Repositories\TravelOrderInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

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
        if ($data['return_date'] <= $data['departure_date']) {
            throw new InvalidArgumentException("The return date must be after the departure date.");
        }

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

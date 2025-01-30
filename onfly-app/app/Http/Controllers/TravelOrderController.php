<?php

namespace App\Http\Controllers;

use App\Http\Requests\TravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Models\TravelOrder;
use App\Services\TravelOrderService;

class TravelOrderController extends Controller
{
    protected $travelOrderService;

    public function __construct(TravelOrderService $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    public function store(TravelOrderRequest $request)
    {
        $validated = $request->validated();

        $travelOrder = $this->travelOrderService->create($validated);

        return response()->json($travelOrder, 201);
    }

    public function updateStatus(UpdateTravelOrderStatusRequest $request, TravelOrder $travelOrder)
    {
        $travelOrder = $this->travelOrderService->update($request->status, $travelOrder);

        return response()->json($travelOrder);
    }
}

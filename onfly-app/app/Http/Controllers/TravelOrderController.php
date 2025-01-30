<?php

namespace App\Http\Controllers;

use App\Http\Requests\TravelOrderRequest;
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
}

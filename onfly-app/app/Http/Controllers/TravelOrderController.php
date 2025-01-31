<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\TravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Models\TravelOrder;
use App\Services\TravelOrderService;
use Illuminate\Http\Request;

class TravelOrderController extends Controller
{
    protected $travelOrderService;

    public function __construct(TravelOrderService $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    public function index(Request $request)
    {
        return ApiResponse::success("", $this->travelOrderService->listTravelOrders($request));
    }

    public function show($id)
    {
        $order = $this->travelOrderService->getTravelOrderById($id);

        return ApiResponse::success("", $order);
    }

    public function store(TravelOrderRequest $request)
    {
        $validated = $request->validated();

        $travelOrder = $this->travelOrderService->create($validated);

        return ApiResponse::created("Order created successfully", $travelOrder);
    }

    public function updateStatus(UpdateTravelOrderStatusRequest $request, TravelOrder $travelOrder)
    {
        $travelOrder = $this->travelOrderService->update($request->status, $travelOrder);

        return ApiResponse::success('Status updated successfully', $travelOrder);
    }
}

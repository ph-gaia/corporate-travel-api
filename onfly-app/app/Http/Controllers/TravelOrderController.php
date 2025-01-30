<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
        ]);

        $travelOrder = TravelOrder::create([
            'user_id' => Auth::id(),
            'destination' => $request->destination,
            'departure_date' => $request->departure_date,
            'return_date' => $request->return_date,
            'status' => 'requested',
        ]);

        return response()->json($travelOrder, 201);
    }
}

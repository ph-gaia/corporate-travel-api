<?php

namespace App\Repositories;

use App\Models\TravelOrder;

class TravelOrderRepository implements TravelOrderInterface
{

    public function create(array $data)
    {
        return TravelOrder::create($data);
    }

    public function listTravelOrders($params)
    {
        $query = TravelOrder::query();

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        if ($params->has('status')) {
            $query->where('status', $params->status);
        }

        if ($params->has('destination')) {
            $query->where('destination', 'LIKE', "%{$params->destination}%");
        }

        if ($params->has('from') && $params->has('to')) {
            $query->whereBetween('departure_date', [$params->from, $params->to]);
        }

        return $query->get();
    }
}

<?php

namespace App\Repositories;

use App\Models\TravelOrder;

class TravelOrderRepository implements TravelOrderInterface
{

    public function create(array $data)
    {
        return TravelOrder::create($data);
    }
}

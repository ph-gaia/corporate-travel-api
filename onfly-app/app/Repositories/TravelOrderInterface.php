<?php

namespace App\Repositories;

interface TravelOrderInterface
{
    public function create(array $data);
    public function listTravelOrders($params);
}
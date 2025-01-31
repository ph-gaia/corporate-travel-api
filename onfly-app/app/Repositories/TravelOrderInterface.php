<?php

namespace App\Repositories;

interface TravelOrderInterface
{
    public function create(array $data);
    public function listTravelOrders($params);
    public function findById(int $id);
}
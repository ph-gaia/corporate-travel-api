<?php

namespace Database\Factories;

use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelOrderFactory extends Factory
{
    protected $model = TravelOrder::class;

    public function definition()
    {
        return [
            'user_id'    => \App\Models\User::factory(),
            'destination'=> $this->faker->city,
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'return_date' => $this->faker->dateTimeBetween('+2 months', '+3 months')->format('Y-m-d'),
            'status'     => 'requested',
        ];
    }
}

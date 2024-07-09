<?php

namespace TestMonitor\CalculatedColumns\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TestMonitor\CalculatedColumns\Test\Models\Order;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company(),
        ];
    }
}

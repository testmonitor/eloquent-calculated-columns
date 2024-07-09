<?php

namespace TestMonitor\CalculatedColumns\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TestMonitor\CalculatedColumns\Test\Models\OrderItem;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'description' => $this->faker->company(),
            'amount' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->numberBetween(10, 10000),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockPriceFactory extends Factory
{
    protected $model = \App\Models\StockPrice::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Test;
use App\Models\TestOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailTestOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'test_order_id' => function () {
                return TestOrder::inRandomOrder()->first()->id;
            },
            'test_id' => function () {
                return Test::inRandomOrder()->first()->id;
            },
            'test_name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'discount' => $this->faker->randomFloat(2, 0, 50),
            'total' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}

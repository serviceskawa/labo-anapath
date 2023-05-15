<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(5),
            'type' => $this->faker->text(5),
        ];
    }
}

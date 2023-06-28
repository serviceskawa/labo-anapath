<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name,
        'email' => $this->faker->unique()->safeEmail,
        'role' => $this->faker->jobTitle,
        'telephone' => $this->faker->phoneNumber,
        'commission' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}

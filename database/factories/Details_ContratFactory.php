<?php

namespace Database\Factories;

use App\Models\CategoryTest;
use App\Models\Contrat;
use Illuminate\Database\Eloquent\Factories\Factory;

class Details_ContratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contrat_id' => function () {
                return Contrat::inRandomOrder()->first()->id;
            },
            'pourcentage' => $this->faker->numberBetween(0, 100),
            'category_test_id' => function () {
                return CategoryTest::inRandomOrder()->first()->id;
            },
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\CategoryTest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = CategoryTest::class;


    public function definition()
    {
        return [
            'code' => $this->faker->numerify('CA-####'),
            'name' => $this->faker->word,
        ];
    }
}

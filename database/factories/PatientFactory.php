<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' =>$this->faker->numberBetween(100,100000),
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'genre' => Str::between('gender', 'Masculin', 'Feminin'),
            'telephone1'=> $this->faker->phoneNumber(),
            'telephone2'=> $this->faker->phoneNumber(),
            'adresse'=> $this->faker->address(),
            'age'=>$this->faker->numberBetween(1,3),
            'profession'=>$this->faker->sentence(1),
            'created_at'=>$this->faker->date(),
            'updated_at'=>$this->faker->date(),
        ];
    }
}
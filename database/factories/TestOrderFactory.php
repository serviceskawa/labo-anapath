<?php

namespace Database\Factories;

use App\Models\Contrat;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class TestOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

        'patient_id' => function () {
            return Patient::inRandomOrder()->first()->id;
        },
        'doctor_id' => function () {
            return Doctor::inRandomOrder()->first()->id;
        },
        'hospital_id' => function () {
            return Hospital::inRandomOrder()->first()->id;
        },
        'reference_hopital' => $this->faker->word,
        'contrat_id' => function () {
            return Contrat::inRandomOrder()->first()->id;
        },
        'prelevement_date' => Carbon::now(),

        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function () {
            DB::rollBack();
        });
    }
}

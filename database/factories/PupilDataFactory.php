<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PupilData>
 */
class PupilDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

public function definition(): array
{
    return [
        'FirstName' => fake()->firstName(),
        'Surname' => fake()->lastName(),
        'YearGroup' => fake()->numberBetween(8, 14),
        'DateOfBirth' => fake()->dateTimeBetween('2007-07-01', '2014-06-30'),
        'Gender' =>fake()->randomElement(['Male','Female','Non-Binary']),
        'FormClass'  => fake()->randomElement(['8A', '9B', '10C', '11D']),
        'SEN' => fake()->optional(0.2)->randomElement(['Sp&Lang','Numeracy']),
        'Medical' => fake()->optional(0.4)->randomElement(['ADD','ADHD','Dyslexia'])
        
    ];
}
}

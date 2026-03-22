<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassLists>
 */
class ClassListsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
// database/factories/ClassListsFactory.php
public function definition(): array
{
    return [
        'ClassName'  => fake()->bothify('??-##'), // Generates random class name
        'YearGroup'  => fake()->numberBetween(8, 14),
        'Subject'    => fake()->randomElement(['Maths', 'Science', 'English', 'History']),
        'teacher_id' => \App\Models\UserData::factory(), // Automatically creates a teacher 
    ];
}
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserDataFactory extends Factory
{

public function definition(): array
{
    return [
        'FirstName' => fake()->firstName(),
        'Surname' => fake()->lastName(),
        'UserEmail' => fake()->unique()->safeEmail(),
        'password' => Hash::make('password'),
        'user_type' => 4, // Default to standard Teacher
    ];
}

// Create admin user for tests
public function admin(): static
{
    return $this->state(fn (array $attributes) => [
        'user_type' => 2,
    ]);
}

// Create standard teacher user for tests
public function teacher(): static
{
    return $this->state(fn () => ['user_type' => 4]);
}

// Create HoD user for tests
public function hod(): static
{
    return $this->state(fn () => ['user_type' => 3]);
}

}
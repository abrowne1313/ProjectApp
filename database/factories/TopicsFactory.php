<?php

namespace Database\Factories;

use App\Models\Topics;
use App\Models\Schemes;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicsFactory extends Factory
{
    protected $model = Topics::class;

    public function definition()
    {
        return [
            'Scheme_id'     => Schemes::factory(),
            'Title'         => fake()->sentence(3),
            'MaxTestScore'  => fake()->numberBetween(10, 100),
            'TeachingOrder' => fake()->numberBetween(1, 20),
        ];
    }
}

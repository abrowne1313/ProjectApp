<?php

namespace Database\Factories;

use App\Models\revisionlists;
use App\Models\Topics;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevisionListsFactory extends Factory
{
    protected $model = revisionlists::class;

    public function definition()
    {
        return [
            'topic_id' => Topics::factory(),
            'content' => fake()->paragraph(),
        ];
    }
}

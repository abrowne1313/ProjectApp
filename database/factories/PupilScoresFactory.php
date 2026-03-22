<?php

namespace Database\Factories;

use App\Models\PupilScores;
use App\Models\PupilData;
use App\Models\Topics;
use Illuminate\Database\Eloquent\Factories\Factory;

class PupilScoresFactory extends Factory
{
    protected $model = PupilScores::class;

    public function definition()
    {
        return [
            'Pupil_id' => PupilData::factory(),
            'Topic_id' => Topics::factory(),
            'Score'    => fake()->numberBetween(0, 20),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\PupilTarget;
use App\Models\PupilData;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class PupilTargetFactory extends Factory
{
    protected $model = PupilTarget::class;

    public function definition()
    {
        return [
            'Pupil_id'   => PupilData::factory(),
            'Subject_id' => Subject::factory(),
            'YearGroup' => fake()->numberBetween(8, 14),
            'Target'     => fake()->numberBetween(10, 90),
        ];
    }
}

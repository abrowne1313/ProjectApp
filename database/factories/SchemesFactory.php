<?php

namespace Database\Factories;

use App\Models\Schemes;
use App\Models\Subject;
use App\Models\UserData;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchemesFactory extends Factory
{
    protected $model = Schemes::class;

    public function definition()
    {
        return [
            'Subject_id' => Subject::factory(),
            'YearGroup'  => fake()->numberBetween(8, 14),
            'CreatedBy'  => UserData::factory(),
        ];
    }
}

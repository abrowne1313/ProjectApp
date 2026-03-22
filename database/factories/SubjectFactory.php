<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\UserData;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'Subject'         => $this->faker->randomElement([
                'Maths', 'English', 'Science', 'History', 'Geography'               
            ]),
            'HoD_Teacher_id'  => UserData::factory()->state([
                'user_type' => 3, // HoD
            ]),
        ];
    }
}

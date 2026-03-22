<?php

namespace Tests\Feature\Classes;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\PupilTarget;
use App\Models\ClassLists;
use App\Models\Subject;
use App\Models\Topics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassScoresTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_scores_and_targets()
    {
        $teacher = UserData::factory()->create();
        $topic = Topics::factory()->create();
        $class = ClassLists::factory()->create([
            'teacher_id' => $teacher->id,
            'Subject' => 'Maths',
            'YearGroup' => 10,
        ]);

        $subject = Subject::factory()->create(['Subject' => 'Maths']);

        $pupil = PupilData::factory()->create();

        $this->actingAs($teacher)
            ->post(route('class.scores.save', $class->id), [
                'targets' => [
                    $pupil->id => 6,
                ],
                'scores' => [
                    $pupil->id => [
                        $topic->id => 5,
                    ],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('pupil_targets', [
            'Pupil_id' => $pupil->id,
            'Target' => 6,
        ]);

        $this->assertDatabaseHas('pupil_scores', [
            'Pupil_id' => $pupil->id,
            'Topic_id' => $topic->id,
            'Score' => 5,
        ]);
    }
}

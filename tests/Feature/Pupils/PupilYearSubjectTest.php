<?php

namespace Tests\Feature\Pupils;

use Tests\TestCase;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\Topics;
use App\Models\Schemes;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PupilYearSubjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_scores_for_specific_year_and_subject()
    {
        $pupil = PupilData::factory()->create();

        $subject = Subject::factory()->create();
        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'YearGroup' => $pupil->YearGroup,
        ]);
        $topic = Topics::factory()->create([
            'Scheme_id' => $scheme->id,
            'TeachingOrder' => 1,
        ]);

        PupilScores::factory()->create([
            'Pupil_id' => $pupil->id,
            'Topic_id' => $topic->id,
            'Score' => 7,
        ]);

        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('pupil.scores.show', [
                'pupil' => $pupil->id,
                'year' => $pupil->YearGroup,
                'subject' => $subject->id,
            ]))
            ->assertStatus(200)
            ->assertViewIs('PupilScoreView')
            ->assertViewHas('topics')
            ->assertViewHas('scores');
    }
}

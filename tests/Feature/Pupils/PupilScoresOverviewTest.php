<?php

namespace Tests\Feature\Pupils;

use Tests\TestCase;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\Topics;
use App\Models\Schemes;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PupilScoresOverviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_loads_the_pupil_overview_page()
    {
        $pupil = PupilData::factory()->create();

        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('pupil.scores.overview', $pupil))
            ->assertStatus(200)
            ->assertViewIs('PupilOverview')
            ->assertViewHas('pupil');
    }

    /** @test */
    public function it_groups_scores_by_year_and_subject()
    {
        $pupil = PupilData::factory()->create();

        $subject = Subject::factory()->create();
        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'YearGroup' => 10,
        ]);
        $topic = Topics::factory()->create([
            'Scheme_id' => $scheme->id,
        ]);

        PupilScores::factory()->create([
            'Pupil_id' => $pupil->id,
            'Topic_id' => $topic->id,
            'Score' => 5,
        ]);

        $response = $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('pupil.scores.overview', $pupil))
            ->assertStatus(200);

        $response->assertViewHas('grouped');
    }
}

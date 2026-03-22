<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PupilScores;
use App\Models\PupilData;
use App\Models\Topics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PupilScoresTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $score = new PupilScores();

        $this->assertEqualsCanonicalizing([
            'Pupil_id',
            'Topic_id',
            'Score',
        ], $score->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_pupil()
    {
        $pupil = PupilData::factory()->create();
        $score = PupilScores::factory()->create(['Pupil_id' => $pupil->id]);

        $this->assertEquals($pupil->id, $score->pupil->id);
    }

    /** @test */
    public function it_belongs_to_a_topic()
    {
        $topic = Topics::factory()->create();
        $score = PupilScores::factory()->create(['Topic_id' => $topic->id]);

        $this->assertEquals($topic->id, $score->topic->id);
    }

    /** @test */
    public function factory_creates_valid_score()
    {
        $score = PupilScores::factory()->create();

        $this->assertNotNull($score->id);
        $this->assertNotNull($score->Score);
    }
}

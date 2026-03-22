<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\PupilTarget;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PupilDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $pupil = new PupilData();

        $this->assertEqualsCanonicalizing([
            'FirstName',
            'Surname',
            'YearGroup',
            'DateOfBirth',
            'Gender',
            'FormClass',
            'SEN',
            'Medical',
        ], $pupil->getFillable());
    }

    /** @test */
    public function it_can_have_scores()
    {
        $pupil = PupilData::factory()->create();
        $score = PupilScores::factory()->create(['Pupil_id' => $pupil->id]);

        $this->assertTrue($pupil->scores->contains($score));
    }

    /** @test */
    public function it_can_have_targets()
    {
        $pupil = PupilData::factory()->create();
        $target = PupilTarget::factory()->create(['Pupil_id' => $pupil->id]);


        $this->assertTrue($pupil->targets->contains($target));
    }

    /** @test */
    public function factory_creates_valid_pupil()
    {
        $pupil = PupilData::factory()->create();

        $this->assertNotNull($pupil->id);
        $this->assertNotEmpty($pupil->FirstName);
    }
}

<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PupilTarget;
use App\Models\PupilData;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PupilTargetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $target = new PupilTarget();

        $this->assertEqualsCanonicalizing([
            'Pupil_id',
            'Subject_id',
            'YearGroup',
            'Target',
        ], $target->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_pupil()
    {
        $pupil = PupilData::factory()->create();
        $target = PupilTarget::factory()->create(['Pupil_id' => $pupil->id]);

        $this->assertEquals($pupil->id, $target->pupil->id);
    }

    /** @test */
    public function it_belongs_to_a_subject()
    {
        $subject = Subject::factory()->create();
        $target = PupilTarget::factory()->create(['Subject_id' => $subject->id,]);

        $this->assertEquals($subject->id, $target->subject->id);
    }

    /** @test */
    public function factory_creates_valid_target()
    {
        $target = PupilTarget::factory()->create();

        $this->assertNotNull($target->id);
        $this->assertNotNull($target->Target);
    }
}

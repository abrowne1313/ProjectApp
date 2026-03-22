<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Subject;
use App\Models\UserData;
use App\Models\Schemes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $subject = new Subject();

        $this->assertEqualsCanonicalizing([
            'Subject',
            'HoD_Teacher_id',
        ], $subject->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_hod_teacher()
    {
        $teacher = UserData::factory()->create();
        $subject = Subject::factory()->create([
            'HoD_Teacher_id' => $teacher->id,
        ]);

        $this->assertEquals($teacher->id, $subject->hodTeacher->id);
    }

    /** @test */
    public function it_can_have_schemes()
    {
        $subject = Subject::factory()->create();
        $scheme = Schemes::factory()->create(['Subject_id' => $subject->id]);

        $this->assertTrue($subject->schemes->contains($scheme));
    }

    /** @test */
    public function factory_creates_valid_subject()
    {
        $subject = Subject::factory()->create();

        $this->assertNotNull($subject->id);
        $this->assertNotEmpty($subject->Subject);
    }
}

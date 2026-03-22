<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ClassLists;
use App\Models\UserData;
use App\Models\PupilData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassListsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $class = new ClassLists();

        $this->assertEqualsCanonicalizing([
            'ClassName',
            'YearGroup',
            'Subject',
            'teacher_id',
        ], $class->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_teacher()
    {
        $teacher = UserData::factory()->create();
        $class = ClassLists::factory()->create(['teacher_id' => $teacher->id]);

        $this->assertEquals($teacher->id, $class->teacher->id);
    }

    /** @test */
    public function it_can_have_pupils()
    {
        $class = ClassLists::factory()->create();
        $pupil = PupilData::factory()->create();

        $class->pupils()->attach($pupil->id);

        $this->assertTrue($class->pupils->contains($pupil));
    }

    /** @test */
    public function factory_creates_valid_class()
    {
        $class = ClassLists::factory()->create();

        $this->assertNotNull($class->id);
        $this->assertNotEmpty($class->ClassName);
    }
}

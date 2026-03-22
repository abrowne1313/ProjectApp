<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Schemes;
use App\Models\Subject;
use App\Models\UserData;
use App\Models\Topics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $scheme = new Schemes();

        $this->assertEqualsCanonicalizing([
            'Subject_id',
            'YearGroup',
            'CreatedBy',
        ], $scheme->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_subject()
    {
        $subject = Subject::factory()->create();
        $scheme = Schemes::factory()->create(['Subject_id' => $subject->id]);

        $this->assertEquals($subject->id, $scheme->subject->id);
    }

    /** @test */
    public function it_belongs_to_a_creator()
    {
        $user = UserData::factory()->create();
        $scheme = Schemes::factory()->create(['CreatedBy' => $user->id]);

        $this->assertEquals($user->id, $scheme->creator->id);
    }

    /** @test */
    public function it_can_have_topics()
    {
        $scheme = Schemes::factory()->create();
        $topic = Topics::factory()->create(['Scheme_id' => $scheme->id]);

        $this->assertTrue($scheme->topics->contains($topic));
    }

    /** @test */
    public function factory_creates_valid_scheme()
    {
        $scheme = Schemes::factory()->create();

        $this->assertNotNull($scheme->id);
        $this->assertNotNull($scheme->Subject_id);
    }
}

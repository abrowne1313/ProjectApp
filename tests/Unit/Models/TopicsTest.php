<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Topics;
use App\Models\Schemes;
use App\Models\revisionLists;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $topic = new Topics();

        $this->assertEqualsCanonicalizing([
            'Scheme_id',
            'Title',
            'TeachingOrder',
            'MaxTestScore',
        ], $topic->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_scheme()
    {
        $scheme = Schemes::factory()->create();
        $topic = Topics::factory()->create(['Scheme_id' => $scheme->id]);

        $this->assertEquals($scheme->id, $topic->scheme->id);
    }

    /** @test */
    public function it_can_have_a_revision_list()
    {
        $topic = Topics::factory()->create();
        $rev = revisionLists::factory()->create(['topic_id' => $topic->id]);

        $this->assertEquals($rev->id, $topic->revisionlist->id);
    }


    /** @test */
    public function factory_creates_valid_topic()
    {
        $topic = Topics::factory()->create();

        $this->assertNotNull($topic->id);
        $this->assertNotEmpty($topic->Title);
    }
}

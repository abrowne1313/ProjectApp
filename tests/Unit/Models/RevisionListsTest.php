<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\revisionLists;
use App\Models\Topics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevisionListsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $rev = new revisionLists();

        $this->assertEqualsCanonicalizing([
            'topic_id',
            'content',
            
        ], $rev->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_topic()
    {
        $topic = Topics::factory()->create();
        $rev = revisionLists::factory()->create(['topic_id' => $topic->id]);

        $this->assertEquals($topic->id, $rev->topic->id);
    }

    /** @test */
    public function factory_creates_valid_revision_list()
    {
        $rev = revisionLists::factory()->create();

        $this->assertNotNull($rev->id);
        $this->assertNotEmpty($rev->content);
    }
}

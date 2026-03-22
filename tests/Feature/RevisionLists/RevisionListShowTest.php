<?php

namespace Tests\Feature\RevisionLists;

use Tests\TestCase;
use App\Models\Topics;
use App\Models\revisionLists;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevisionListShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_revision_list_for_a_topic()
    {
        $topic = Topics::factory()->create();
        $revision = revisionLists::factory()->create([
            'topic_id' => $topic->id,
            'content' => 'Test revision content',
        ]);

        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('revisionlists.show', $topic->id))
            ->assertStatus(200)
            ->assertViewIs('revisionListView')
            ->assertViewHas('topic')
            ->assertViewHas('revisionlist', $revision);
    }

    /** @test */
    public function it_handles_topics_with_no_revision_list()
    {
        $topic = Topics::factory()->create();

        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('revisionlists.show', $topic->id))
            ->assertStatus(200)
            ->assertViewHas('revisionlist', null);
    }
}

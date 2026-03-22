<?php

namespace Tests\Feature\RevisionLists;

use Tests\TestCase;
use App\Models\Topics;
use App\Models\revisionLists;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevisionListStoreUpdateDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function user()
    {
        return \App\Models\UserData::factory()->create();
    }

    /** @test */
    public function it_creates_a_revision_list_for_a_topic()
    {
        $topic = Topics::factory()->create();

        $this->actingAs($this->user())
            ->post(route('revisionlists.save', $topic->id), [
                'content' => 'New revision content',
                'url' => 'http://example.com',
            ])
            ->assertRedirect(route('topic.show', $topic->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('revisionlists', [
            'topic_id' => $topic->id,
            'content' => 'New revision content',
        ]);
    }

    /** @test */
    public function it_updates_an_existing_revision_list()
    {
        $topic = Topics::factory()->create();
        $revision = revisionLists::factory()->create([
            'topic_id' => $topic->id,
            'content' => 'Old content',
        ]);

        $this->actingAs($this->user())
            ->post(route('revisionlists.save', $topic->id), [
                'content' => 'Updated content',
            ])
            ->assertRedirect(route('topic.show', $topic->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('revisionlists', [
            'id' => $revision->id,
            'content' => 'Updated content',
        ]);
    }

    /** @test */
    public function content_is_required_when_saving_revision_list()
    {
        $topic = Topics::factory()->create();

        $this->actingAs($this->user())
            ->post(route('revisionlists.save', $topic->id), [])
            ->assertSessionHasErrors('content');
    }

    /** @test */
    public function it_deletes_a_revision_list()
    {
        $topic = Topics::factory()->create();
        $revision = revisionLists::factory()->create([
            'topic_id' => $topic->id,
        ]);

        $this->actingAs($this->user())
            ->delete(route('revisionlists.delete', $topic->id))
            ->assertRedirect(route('revisionlists.show', $topic->id))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('revisionlists', [
            'id' => $revision->id,
        ]);
    }

    /** @test */
    public function deleting_a_topic_with_no_revision_list_still_redirects_successfully()
    {
        $topic = Topics::factory()->create();

        $this->actingAs($this->user())
            ->delete(route('revisionlists.delete', $topic->id))
            ->assertRedirect(route('revisionlists.show', $topic->id))
            ->assertSessionHas('success');
    }
}

<?php

namespace Tests\Feature\Topics;

use Tests\TestCase;
use App\Models\Topics;
use App\Models\Schemes;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_a_topic_with_related_scheme_and_subject()
    {
        $subject = Subject::factory()->create();
        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
        ]);

        $topic = Topics::factory()->create([
            'Scheme_id' => $scheme->id,
        ]);

        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('topic.show', $topic->id))
            ->assertStatus(200)
            ->assertViewIs('HoDControls.TopicView')
            ->assertViewHas('topic', function ($t) use ($topic) {
                return $t->id === $topic->id;
            });
    }

    /** @test */
    public function it_returns_404_for_missing_topic()
    {
        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('topic.show', 999999))
            ->assertStatus(404);
    }
}

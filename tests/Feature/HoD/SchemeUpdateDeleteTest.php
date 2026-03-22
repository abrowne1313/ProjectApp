<?php

namespace Tests\Feature\HoD;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\Schemes;
use App\Models\Topics;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchemeUpdateDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function hod()
    {
        return UserData::factory()->state(['user_type' => 2])->create();
    }

    /** @test */
    public function hod_can_update_existing_topics()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'CreatedBy' => $hod->id,
        ]);

        $topic = Topics::factory()->create([
            'Scheme_id' => $scheme->id,
            'Title' => 'Old Title',
            'TeachingOrder' => 1,
        ]);

        $this->actingAs($hod)
            ->put(route('schemes.update', $scheme->id), [
                'topics' => [
                    $topic->id => [
                        'Title' => 'Updated Title',
                        'MaxTestScore' => 15,
                        'TeachingOrder' => 1,
                    ],
                ],
            ])
            ->assertRedirect(route('schemes.show', $scheme->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
            'Title' => 'Updated Title',
            'MaxTestScore' => 15,
        ]);
    }

    /** @test */
    public function hod_can_add_new_topics()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'CreatedBy' => $hod->id,
        ]);

        $this->actingAs($hod)
            ->put(route('schemes.update', $scheme->id), [
                'topics' => [],
                'new_topics' => [
                    [
                        'Title' => 'New Topic',
                        'MaxTestScore' => 20,
                        'TeachingOrder' => 1,
                    ],
                ],
            ])
            ->assertRedirect(route('schemes.show', $scheme->id));

        $this->assertDatabaseHas('topics', [
            'Scheme_id' => $scheme->id,
            'Title' => 'New Topic',
        ]);
    }

    /** @test */
    public function hod_can_delete_removed_topics()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'CreatedBy' => $hod->id,
        ]);

        $topic = Topics::factory()->create([
            'Scheme_id' => $scheme->id,
        ]);

        $this->actingAs($hod)
            ->put(route('schemes.update', $scheme->id), [
                'topics' => [], // topic removed
            ])
            ->assertRedirect(route('schemes.show', $scheme->id));

        $this->assertDatabaseMissing('topics', [
            'id' => $topic->id,
        ]);
    }

    /** @test */
    public function hod_can_delete_a_single_topic()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $topic = Topics::factory()->create();

        $this->actingAs($hod)
            ->delete(route('scheme.topic.delete', $topic->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('topics', [
            'id' => $topic->id,
        ]);
    }
}

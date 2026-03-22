<?php

namespace Tests\Feature\HoD;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\Subject;
use App\Models\Schemes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectOverviewTest extends TestCase
{
    use RefreshDatabase;

    protected function admin()
    {
        return UserData::factory()->admin()->create();
    }

    protected function hod()
    {
        return UserData::factory()->state(['user_type' => 3])->create();
    }

    protected function teacher()
    {
        return UserData::factory()->state(['user_type' => 4])->create();
    }

    /** @test */
    public function admin_can_view_all_subjects()
    {
        $admin = $this->admin();
        $subjects = Subject::factory()->count(3)->create();

        $this->actingAs($admin)
            ->get(route('subject.overview'))
            ->assertStatus(200)
            ->assertViewIs('HoDControls.subjectoverview')
            ->assertViewHas('subjects', function ($s) use ($subjects) {
                return $s->count() === 3;
            });
    }

    /** @test */
    public function hod_sees_only_their_own_subjects()
    {
        $hod = $this->hod();

        $mine = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);
        Subject::factory()->create(); // belongs to someone else

        $this->actingAs($hod)
            ->get(route('subject.overview'))
            ->assertStatus(200)
            ->assertViewHas('subjects', function ($subjects) use ($mine) {
                return $subjects->count() === 1 &&
                       $subjects->first()->id === $mine->id;
            });
    }

    /** @test */
    public function teacher_cannot_view_subject_overview()
    {
        $teacher = $this->teacher();

        $this->actingAs($teacher)
            ->get(route('subject.overview'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_403s_if_no_subjects_available()
    {
        $hod = $this->hod();

        $this->actingAs($hod)
            ->get(route('subject.overview'))
            ->assertStatus(403);
    }

    /** @test */
    public function it_sets_active_subject_from_request()
    {
        $hod = $this->hod();

        $subject1 = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);
        $subject2 = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $this->actingAs($hod)
            ->get(route('subject.overview', ['subject_id' => $subject2->id]))
            ->assertStatus(200)
            ->assertViewHas('activeSubject', function ($active) use ($subject2) {
                return $active->id === $subject2->id;
            });
    }

    /** @test */
    public function it_loads_schemes_with_topic_counts()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
        ]);

        // Add topics
        \App\Models\Topics::factory()->count(3)->create([
            'Scheme_id' => $scheme->id,
        ]);

        $this->actingAs($hod)
            ->get(route('subject.overview'))
            ->assertStatus(200)
            ->assertViewHas('schemes', function ($schemes) {
                return $schemes->first()->topics_count === 3;
            });
    }
}

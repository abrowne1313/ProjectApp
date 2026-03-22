<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubjectManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function admin()
    {
        return UserData::factory()->admin()->create();
    }

    protected function teacher()
    {
        return UserData::factory()->state(['user_type' => 4])->create();
    }

    /** @test */
    public function admin_can_view_subject_manager()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('subject.manager'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.SubjectManager')
            ->assertViewHas('subjects');
    }

    /** @test */
    public function non_admin_cannot_view_subject_manager()
    {
        $teacher = $this->teacher();

        $this->actingAs($teacher)
            ->get(route('subject.manager'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_view_create_subject_form()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('subject.create'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.AddSubject')
            ->assertViewHas('teachers');
    }

    /** @test */
    public function admin_can_create_a_subject()
    {
        $admin = $this->admin();
        $teacher = $this->teacher();

        $this->actingAs($admin)
            ->post(route('subject.store'), [
                'Subject' => 'Mathematics',
                'HoD_Teacher_id' => $teacher->id,
            ])
            ->assertRedirect(route('subject.manager'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subjects', [
            'Subject' => 'Mathematics',
            'HoD_Teacher_id' => $teacher->id,
        ]);
    }

    /** @test */
    public function creating_subject_requires_valid_data()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('subject.store'), [])
            ->assertSessionHasErrors(['Subject', 'HoD_Teacher_id']);
    }

    /** @test */
    public function admin_can_view_edit_subject_form()
    {
        $admin = $this->admin();
        $subject = Subject::factory()->create();

        $this->actingAs($admin)
            ->get(route('subject.edit', $subject))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.EditSubject')
            ->assertViewHas('subject')
            ->assertViewHas('teachers');
    }

    /** @test */
    public function admin_can_update_a_subject()
    {
        $admin = $this->admin();
        $subject = Subject::factory()->create();
        $teacher = $this->teacher();

        $this->actingAs($admin)
            ->put(route('subject.update', $subject), [
                'Subject' => 'Updated Subject',
                'HoD_Teacher_id' => $teacher->id,
            ])
            ->assertRedirect(route('subject.manager'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'Subject' => 'Updated Subject',
            'HoD_Teacher_id' => $teacher->id,
        ]);
    }

    /** @test */
    public function updating_subject_requires_valid_data()
    {
        $admin = $this->admin();
        $subject = Subject::factory()->create();

        $this->actingAs($admin)
            ->put(route('subject.update', $subject), [])
            ->assertSessionHasErrors(['Subject', 'HoD_Teacher_id']);
    }

    /** @test */
    public function admin_can_delete_a_subject()
    {
        $admin = $this->admin();
        $subject = Subject::factory()->create();

        $this->actingAs($admin)
            ->delete(route('subject.destroy', $subject))
            ->assertRedirect(route('subject.manager'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
        ]);
    }
}

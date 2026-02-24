<?php

namespace Tests\Feature;

use App\Models\UserData;
use App\Models\ClassLists;
use App\Models\PupilData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassListsTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser()
    {
        return UserData::factory()->create([
            'user_type' => 1, // Admin
        ]);
    }

    protected function teacherUser()
    {
        return UserData::factory()->create([
            'user_type' => 4, // Teacher
        ]);
    }

    /** @test */
    public function admin_can_view_class_manager()
    {
        $this->actingAs($this->adminUser())
            ->get(route('class.manager'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.classmanager');
    }

    /** @test */
    public function admin_can_create_a_class()
    {
        $admin = $this->adminUser();
        $teacher = $this->teacherUser();

        $this->actingAs($admin)
            ->post(route('classlists.store'), [
                'ClassName' => 'Maths 101',
                'Subject' => 'Maths',
                'teacher_id' => $teacher->id,
            ])
            ->assertRedirect(route('CreateClass'));

        $this->assertDatabaseHas('class_lists', [
            'ClassName' => 'Maths 101',
            'Subject' => 'Maths',
        ]);
    }

    /** @test */
    public function class_creation_fails_with_invalid_data()
    {
        $this->actingAs($this->adminUser())
            ->post(route('classlists.store'), [])
            ->assertSessionHasErrors([
                'ClassName',
                'Subject',
                'teacher_id',
            ]);
    }

    /** @test */
    public function admin_can_update_a_class()
    {
        $admin = $this->adminUser();
        $teacher = $this->teacherUser();

        $class = ClassLists::factory()->create();

        $this->actingAs($admin)
            ->put(route('class.update', $class), [
                'ClassName' => 'Updated Class',
                'Subject' => 'Science',
                'teacher_id' => $teacher->id,
            ])
            ->assertRedirect(route('class.manager'));

        $this->assertDatabaseHas('class_lists', [
            'id' => $class->id,
            'ClassName' => 'Updated Class',
        ]);
    }

    /** @test */
    public function admin_can_delete_a_class()
    {
        $admin = $this->adminUser();
        $class = ClassLists::factory()->create();

        $this->actingAs($admin)
            ->delete(route('class.destroy', $class))
            ->assertRedirect(route('class.manager'));

        $this->assertDatabaseMissing('class_lists', [
            'id' => $class->id,
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_class_pupils()
    {
        $user = $this->teacherUser();
        $class = ClassLists::factory()->create();

        $this->actingAs($user)
            ->get(route('class.pupils', $class))
            ->assertStatus(200)
            ->assertViewIs('ClassPupilList');
    }

    /** @test */
    public function admin_can_add_pupil_to_class()
    {
        $admin = $this->adminUser();
        $class = ClassLists::factory()->create();
        $pupil = PupilData::factory()->create();

        $this->actingAs($admin)
            ->post(route('class.pupil.add', $class), [
                'pupil_id' => $pupil->id,
            ])
            ->assertSessionHas('success');

        $this->assertTrue(
            $class->pupils()->where('pupil_id', $pupil->id)->exists()
        );
    }

    /** @test */
    public function admin_can_remove_pupil_from_class()
    {
        $admin = $this->adminUser();
        $class = ClassLists::factory()->create();
        $pupil = PupilData::factory()->create();

        $class->pupils()->attach($pupil->id);

        $this->actingAs($admin)
            ->delete(route('class.pupil.remove', [$class, $pupil]))
            ->assertSessionHas('success');

        $this->assertFalse(
            $class->pupils()->where('pupil_id', $pupil->id)->exists()
        );
    }
}

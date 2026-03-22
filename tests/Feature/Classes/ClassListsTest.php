<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\ClassLists;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\PupilTarget;
use App\Models\Subject;
use App\Models\Schemas;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassListsTest extends TestCase
{
    use RefreshDatabase;

    protected function admin()
    {
        return UserData::factory()->create(['user_type' => 2]);
    }

    /** @test */
    public function admin_can_view_create_class_form()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('CreateClass'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.CreateClass')
            ->assertViewHas('teachers')
            ->assertViewHas('subjects');
    }

    /** @test */
    public function admin_can_store_a_new_class()
    {
        $admin = $this->admin();
        $teacher = UserData::factory()->create();
        $subject = Subject::factory()->create();

        $this->actingAs($admin)
            ->post(route('CreateClass'), [
                'ClassName' => '7A',
                'YearGroup' => '7',
                'Subject' => $subject->Subject,
                'teacher_id' => $teacher->id,
            ])
            ->assertRedirect(route('CreateClass'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('class_lists', [
            'ClassName' => '7A',
            'YearGroup' => '7',
            'Subject' => $subject->Subject,
            'teacher_id' => $teacher->id,
        ]);
    }

    /** @test */
    public function admin_can_view_class_manager()
    {
        $admin = $this->admin();
        ClassLists::factory()->count(2)->create();

        $this->actingAs($admin)
            ->get(route('class.manager'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.classmanager')
            ->assertViewHas('classes');
    }

    /** @test */
    public function admin_can_view_edit_class_form()
    {
        $admin = $this->admin();
        $class = ClassLists::factory()->create();

        $this->actingAs($admin)
            ->get(route('class.edit', $class))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.EditClass')
            ->assertViewHas('class')
            ->assertViewHas('teachers');
    }

    /** @test */
    public function admin_can_update_a_class()
    {
        $admin = $this->admin();
        $class = ClassLists::factory()->create();
        $teacher = UserData::factory()->create();

        $this->actingAs($admin)
            ->put(route('class.update', $class), [
                'ClassName' => 'Updated Class',
                'Subject' => 'Maths',
                'teacher_id' => $teacher->id,
            ])
            ->assertRedirect(route('class.manager'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('class_lists', [
            'id' => $class->id,
            'ClassName' => 'Updated Class',
            'Subject' => 'Maths',
            'teacher_id' => $teacher->id,
        ]);
    }

    /** @test */
    public function admin_can_delete_a_class()
    {
        $admin = $this->admin();
        $class = ClassLists::factory()->create();

        $this->actingAs($admin)
            ->delete(route('class.destroy', $class))
            ->assertRedirect(route('class.manager'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('class_lists', [
            'id' => $class->id,
        ]);
    }

    /** @test */
    public function admin_can_view_pupils_in_class()
    {
        $admin = $this->admin();

        $subject = Subject::factory()->create();
        $class = ClassLists::factory()->create([
            'Subject' => $subject->Subject,
            'YearGroup' => '10',
        ]);

        $pupil = PupilData::factory()->create();
        $class->pupils()->attach($pupil->id);

        $this->actingAs($admin)
            ->get(route('class.pupils', $class->id))
            ->assertStatus(200)
            ->assertViewIs('ClassPupilList')
            ->assertViewHas('class')
            ->assertViewHas('topics')
            ->assertViewHas('scores')
            ->assertViewHas('targets')
            ->assertViewHas('availablePupils');
    }

    /** @test */
    public function admin_can_add_pupil_to_class()
    {
        $admin = $this->admin();
        $class = ClassLists::factory()->create();
        $pupil = PupilData::factory()->create();

        $this->actingAs($admin)
            ->post(route('class.pupil.add', $class), [
                'pupil_id' => $pupil->id,
            ])
            ->assertSessionHas('success');

        $this->assertTrue($class->fresh()->pupils->contains($pupil));
    }

    /** @test */
    public function admin_can_remove_pupil_from_class()
    {
        $admin = $this->admin();
        $class = ClassLists::factory()->create();
        $pupil = PupilData::factory()->create();

        $class->pupils()->attach($pupil->id);

        $this->actingAs($admin)
            ->delete(route('class.pupil.remove', [$class, $pupil]))
            ->assertSessionHas('success');

        $this->assertFalse($class->fresh()->pupils->contains($pupil));
    }
}

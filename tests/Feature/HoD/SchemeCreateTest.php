<?php

namespace Tests\Feature\HoD;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\Schemes;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchemeCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function hod()
    {
        return UserData::factory()->state(['user_type' => 2])->create();
    }

    protected function teacher()
    {
        return UserData::factory()->create(); // user_type = 3
    }

    /** @test */
    public function hod_can_view_create_scheme_form()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $this->actingAs($hod)
            ->get(route('schemes.create'))
            ->assertStatus(200)
            ->assertViewIs('HoDControls.CreateScheme')
            ->assertViewHas('subject');
    }

    /** @test */
    public function non_hod_cannot_view_create_scheme_form()
    {
        $teacher = $this->teacher();

        $this->actingAs($teacher)
            ->get(route('schemes.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function hod_can_create_a_scheme_with_topics()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $data = [
            'YearGroup' => 10,
            'topics' => ['Algebra', 'Geometry'],
            'max_scores' => [20, 25],
        ];

        $this->actingAs($hod)
            ->post('/schemes', $data)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('schemes', [
            'Subject_id' => $subject->id,
            'YearGroup' => 10,
            'CreatedBy' => $hod->id,
        ]);

        $this->assertDatabaseHas('topics', [
            'Title' => 'Algebra',
            'TeachingOrder' => 1,
            'MaxTestScore' => 20,
        ]);

        $this->assertDatabaseHas('topics', [
            'Title' => 'Geometry',
            'TeachingOrder' => 2,
            'MaxTestScore' => 25,
        ]);
    }

    /** @test */
    public function creating_scheme_requires_valid_data()
    {
        $hod = $this->hod();
        Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $this->actingAs($hod)
            ->post('/schemes', [])
            ->assertSessionHasErrors([
                'YearGroup',
                'topics',
            ]);
    }
}

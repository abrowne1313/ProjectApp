<?php

namespace Tests\Feature\HoD;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\PupilData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePupilTest extends TestCase
{
    use RefreshDatabase;

    protected function hod()
    {
        return UserData::factory()->state(['user_type' => 3])->create();
    }

    protected function teacher()
    {
        return UserData::factory()->state(['user_type' => 4])->create(); 
    }

    /** @test */
    public function hod_can_view_create_pupil_form()
    {
        $hod = $this->hod();

        $this->actingAs($hod)
            ->get(route('CreatePupil'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.createpupil');
    }

    /** @test */
    public function non_hod_cannot_view_create_pupil_form()
    {
        $teacher = $this->teacher();

        $this->actingAs($teacher)
            ->get(route('CreatePupil'))
            ->assertStatus(403);
    }

    /** @test */
    public function hod_can_create_a_pupil()
    {
        $hod = $this->hod();

        $data = [
            'FirstName' => 'Alice',
            'Surname' => 'Smith',
            'YearGroup' => '10',
            'DateOfBirth' => '2010-05-10',
            'Gender' => 'Female',
            'FormClass' => '10A',
            'SEN' => 'Dyslexia',
            'Medical' => 'Asthma',
        ];

        $this->actingAs($hod)
            ->post(route('pupildata.store'), $data)
            ->assertRedirect(route('CreatePupil'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pupil_data', [
            'FirstName' => 'Alice',
            'Surname' => 'Smith',
        ]);
    }

    /** @test */
    public function creating_pupil_requires_valid_data()
    {
        $hod = $this->hod();

        $this->actingAs($hod)
            ->post(route('pupildata.store'), [])
            ->assertSessionHasErrors([
                'FirstName',
                'Surname',
                'YearGroup',
                'DateOfBirth',
                'Gender',
                'FormClass',
            ]);
    }

    /** @test */
    public function date_of_birth_must_be_before_today()
    {
        $hod = $this->hod();

        $this->actingAs($hod)
            ->post(route('pupildata.store'), [
                'FirstName' => 'Test',
                'Surname' => 'User',
                'YearGroup' => '10',
                'DateOfBirth' => now()->addDay()->format('Y-m-d'), // invalid
                'Gender' => 'Male',
                'FormClass' => '10A',
            ])
            ->assertSessionHasErrors('DateOfBirth');
    }
}

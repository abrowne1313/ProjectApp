<?php

namespace Tests\Feature;

use App\Models\UserData;
use App\Models\PupilData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PupilDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: create an admin user
     */
    protected function adminUser()
    {
        return UserData::factory()->create([
            'user_type' => 1, // admin
        ]);
    }

    /** @test */
    public function admin_can_view_create_pupil_form()
    {
        $this->actingAs($this->adminUser())
            ->get(route('CreatePupil'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.createpupil');
    }

    /** @test */
    public function admin_can_create_a_pupil_with_valid_data()
    {
        $this->actingAs($this->adminUser())
            ->post(route('pupildata.store'), [
                'FirstName' => 'John',
                'Surname' => 'Doe',
                'DateOfBirth' => '2010-05-10',
                'Gender' => 'Male',
                'FormClass' => '10A',
                'SEN' => null,
                'Medical' => null,
            ])
            ->assertRedirect(route('CreatePupil'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pupil_data', [
            'FirstName' => 'John',
            'Surname' => 'Doe',
            'FormClass' => '10A',
        ]);
    }

    /** @test */
    public function pupil_creation_fails_when_required_fields_are_missing()
    {
        $this->actingAs($this->adminUser())
            ->post(route('pupildata.store'), [])
            ->assertSessionHasErrors([
                'FirstName',
                'Surname',
                'DateOfBirth',
                'Gender',
                'FormClass',
            ]);
    }

    /** @test */
    public function date_of_birth_must_be_in_the_past()
    {
        $this->actingAs($this->adminUser())
            ->post(route('pupildata.store'), [
                'FirstName' => 'Jane',
                'Surname' => 'Doe',
                'DateOfBirth' => now()->addDay()->format('Y-m-d'),
                'Gender' => 'Female',
                'FormClass' => '9B',
            ])
            ->assertSessionHasErrors('DateOfBirth');
    }
}

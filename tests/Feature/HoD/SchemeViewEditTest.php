<?php

namespace Tests\Feature\HoD;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\Schemes;
use App\Models\Subject;
use App\Models\Topics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchemeViewEditTest extends TestCase
{
    use RefreshDatabase;

    protected function hod()
    {
        return UserData::factory()->state(['user_type' => 2])->create();
    }

    /** @test */
    public function hod_can_view_a_scheme()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'CreatedBy' => $hod->id,
        ]);

        Topics::factory()->count(3)->create([
            'Scheme_id' => $scheme->id,
        ]);

        $this->actingAs($hod)
            ->get(route('schemes.show', $scheme->id))
            ->assertStatus(200)
            ->assertViewIs('HoDControls.SchemeView')
            ->assertViewHas('scheme');
    }

    /** @test */
    public function hod_can_view_edit_scheme_page()
    {
        $hod = $this->hod();
        $subject = Subject::factory()->create(['HoD_Teacher_id' => $hod->id]);

        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'CreatedBy' => $hod->id,
        ]);

        Topics::factory()->count(2)->create([
            'Scheme_id' => $scheme->id,
        ]);

        $this->actingAs($hod)
            ->get(route('scheme.edit', $scheme->id))
            ->assertStatus(200)
            ->assertViewIs('HoDControls.EditScheme')
            ->assertViewHas('scheme');
    }
}

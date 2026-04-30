<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\PupilData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PupilManagerTest extends TestCase
{
    use RefreshDatabase;
protected function setUp(): void
{
    parent::setUp();
    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
}

    protected function admin()
    {
        return UserData::factory()->admin()->create();
    }

        protected function HoD()
    {
        return UserData::factory()->hod()->create();
    }

    protected function teacher()
    {
        return UserData::factory()->create(); // user_type = 3
    }

    /** @test */
    public function admin_can_view_pupil_manager()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('pupil.manager'))
            ->assertStatus(200)
            ->assertViewIs('Admincontrols.PupilManager')
            ->assertViewHas('pupils');
    }

    /** @test */
    public function teacher_user_cannot_view_pupil_manager()
    {
        $teacher = $this->teacher();

        $this->actingAs($teacher)
            ->get(route('pupil.manager'))
            
            ->assertStatus(403);

    }


    /** @test */
    public function hod_cannot_view_pupil_manager()
    {
            $hod = $this->hod()    ;
                $this->actingAs($hod)
            ->get(route('pupil.manager'))
            ->assertStatus(403);
    }
    /** @test */
    public function admin_can_delete_a_pupil()
    {
        $admin = $this->admin();
        $pupil = PupilData::factory()->create();

        $this->actingAs($admin)
            ->delete('/pupils/' . $pupil->id)
            ->assertRedirect(route('pupil.manager'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pupil_data', [
            'id' => $pupil->id,
        ]);
    }

    /** @test */
    public function non_admin_cannot_delete_a_pupil()
    {
        $teacher = $this->teacher();
        $pupil = PupilData::factory()->create();

        $this->actingAs($teacher)
            ->delete('/pupils/' . $pupil->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('pupil_data', [
            'id' => $pupil->id,
        ]);
    }

    /** @test */
    public function deleting_nonexistent_pupil_returns_404()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->delete('/pupils/999999')
            ->assertStatus(404);
    }
}

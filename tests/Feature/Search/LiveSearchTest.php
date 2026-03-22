<?php

namespace Tests\Feature\Search;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\PupilData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiveSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function admin()
    {
        return UserData::factory()->admin()->create();
    }

    protected function teacher()
    {
        return UserData::factory()->state(['user_type' => 3])->create();
    }

    /** @test */
    public function it_returns_empty_array_if_query_too_short()
    {
        $this->actingAs($this->teacher())
            ->get('/live-search?q=a')
            ->assertExactJson([]);
    }

    /** @test */
    public function it_returns_matching_pupils()
    {
        $teacher = $this->teacher();
        $pupil = PupilData::factory()->create(['FirstName' => 'Charlie']);

        $this->actingAs($teacher)
            ->get('/live-search?q=Cha')
            ->assertStatus(200)
            ->assertJsonFragment([
                'type' => 'Pupil',
                'label' => 'Charlie ' . $pupil->Surname,
            ]);
    }

    /** @test */
    public function teachers_do_not_receive_user_results()
    {
        $teacher = $this->teacher();
        UserData::factory()->create(['FirstName' => 'AdminUser']);

        $this->actingAs($teacher)
            ->get('/live-search?q=Admin')
            ->assertStatus(200)
            ->assertJsonMissing(['type' => 'User']);
    }

    /** @test */
    public function admins_receive_user_results()
    {
        $admin = $this->admin();
        $user = UserData::factory()->create(['FirstName' => 'Zara']);

        $this->actingAs($admin)
            ->get('/live-search?q=Za')
            ->assertStatus(200)
            ->assertJsonFragment([
                'type' => 'User',
                'label' => 'Zara ' . $user->Surname,
            ]);
    }

    /** @test */
    public function returned_json_contains_correct_urls()
    {
        $admin = $this->admin();
        $pupil = PupilData::factory()->create(['FirstName' => 'Liam']);
        $user = UserData::factory()->create(['FirstName' => 'Lilburn']);

        $response = $this->actingAs($admin)
            ->get('/live-search?q=li');

        $response->assertJsonFragment([
            'url' => route('pupil.scores.overview', $pupil->id),
        ]);

        $response->assertJsonFragment([
            'url' => route('userdata.showAdminView', $user->id),
        ]);
    }
}

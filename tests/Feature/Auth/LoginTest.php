<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\UserData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_loads()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertViewIs('login');
    }

    /** @test */
    public function user_can_log_in_with_correct_credentials()
    {
        $user = UserData::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $this->post(route('login.submit'), [
            'UserEmail' => $user->UserEmail,
            'password' => 'secret123',
        ])
        ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_wrong_password()
    {
        $user = UserData::factory()->create([
            'password' => bcrypt('correctpass'),
        ]);

        $this->post(route('login.submit'), [
            'UserEmail' => $user->UserEmail,
            'password' => 'wrongpass',
        ])
        ->assertSessionHasErrors('UserEmail');

        $this->assertGuest();
    }

    /** @test */
    public function login_requires_email_and_password()
    {
        $this->post(route('login.submit'), [])
            ->assertSessionHasErrors(['UserEmail', 'password']);
    }

    /** @test */
    public function dashboard_requires_authentication()
    {
        $this->get('/dashboard')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_dashboard()
    {
        $user = UserData::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertViewIs('dashboard');
    }
}

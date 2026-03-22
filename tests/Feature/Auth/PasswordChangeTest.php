<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\UserData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_password_change_page()
    {
        $this->get(route('ChangePassword'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_password_change_page()
    {
        $user = UserData::factory()->create();

        $this->actingAs($user)
            ->get(route('ChangePassword'))
            ->assertStatus(200)
            ->assertViewIs('ChangePassword');
    }

    /** @test */
    public function user_can_change_password_with_correct_old_password()
    {
        $user = UserData::factory()->create([
            'password' => Hash::make('oldpass123'),
        ]);

        $this->actingAs($user)
            ->post(route('ChangePassword.submit'), [
                'oldpassword' => 'oldpass123',
                'newpassword1' => 'newpass456',
                'newpassword2' => 'newpass456',
            ])
            ->assertRedirect('/dashboard')
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('newpass456', $user->fresh()->password));
    }

    /** @test */
    public function password_change_fails_with_wrong_old_password()
    {
        $user = UserData::factory()->create([
            'password' => Hash::make('correctold'),
        ]);

        $this->actingAs($user)
            ->post(route('ChangePassword.submit'), [
                'oldpassword' => 'incorrectold',
                'newpassword1' => 'newpass123',
                'newpassword2' => 'newpass123',
            ])
            ->assertSessionHasErrors('oldpassword');

        $this->assertTrue(Hash::check('correctold', $user->fresh()->password));
    }

    /** @test */
    public function password_change_requires_all_fields()
    {
        $user = UserData::factory()->create();

        $this->actingAs($user)
            ->post(route('ChangePassword.submit'), [])
            ->assertSessionHasErrors(['oldpassword', 'newpassword1', 'newpassword2']);
    }

    /** @test */
    public function new_passwords_must_match()
    {
        $user = UserData::factory()->create([
            'password' => Hash::make('oldpass'),
        ]);

        $this->actingAs($user)
            ->post(route('ChangePassword.submit'), [
                'oldpassword' => 'oldpass',
                'newpassword1' => 'newpass123',
                'newpassword2' => 'differentpass',
            ])
            ->assertSessionHasErrors('newpassword2');
    }
}

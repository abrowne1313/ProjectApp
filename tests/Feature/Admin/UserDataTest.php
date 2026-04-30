<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\UserData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;


class UserDataTest extends TestCase
{
    use RefreshDatabase;



    protected function admin()
    {
        return UserData::factory()->admin()->create();
    }

    protected function teacher()
    {
        return UserData::factory()->create();
    }



    /** @test */
    public function non_admin_cannot_access_admin_routes()
    {
        $teacher = $this->teacher();

        $routes = [
            '/admin',
            '/user_manager',
            '/create-user',
            '/admin/users/1/edit',
            '/admin/users/search',
            '/change-password/1',
            '/userinfofull/1',
        ];

        foreach ($routes as $route) {
            $this->actingAs($teacher)
                ->get($route)
                ->assertStatus(403);
        }
    }

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('AdminControls'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.admin');
    }



    /** @test */
    public function admin_can_view_create_user_form()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('CreateUser'))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.createuser');
    }

    /** @test */
    public function admin_can_create_a_user()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('CreateUser'), [
                'FirstName' => 'John',
                'Surname' => 'Doe',
                'user_type' => 3,
                'UserEmail' => 'john@example.com',
                'password' => 'secret123',
            ])
            ->assertRedirect(route('CreateUser'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('user_data', [
            'UserEmail' => 'john@example.com',
        ]);
    }

    /** @test */
    public function user_creation_requires_valid_data()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('CreateUser'), [])
            ->assertSessionHasErrors([
                'FirstName',
                'Surname',
                'user_type',
                'UserEmail',
                'password',
            ]);
    }

    /** @test */
    public function user_creation_fails_with_duplicate_email()
    {
        $admin = $this->admin();
        UserData::factory()->create(['UserEmail' => 'duplicate@example.com']);

        $this->actingAs($admin)
            ->post(route('CreateUser'), [
                'FirstName' => 'Jane',
                'Surname' => 'Smith',
                'user_type' => 3,
                'UserEmail' => 'duplicate@example.com',
                'password' => 'password123',
            ])
            ->assertSessionHasErrors('UserEmail');
    }
/** @test */
public function admin_can_view_user_profile()
{
        $admin = $this->admin();
        $user = $this->teacher();

    $this->actingAs($admin)
        ->get(route('userdata.showAdminView',$user))  
        ->assertStatus(200)
        ->assertViewIs('admincontrols.UserDetails')
        ->assertViewHas('user', $user);
}


    /** @test */
    public function admin_can_view_edit_user_page()
    {
        $admin = $this->admin();
        $user = $this->teacher();

        $this->actingAs($admin)
            ->get(route('userdata.edit', $user))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.edituserdata')
            ->assertViewHas('user');
    }
/** @test */
public function non_admin_cannot_view_edit_user_page()
{
    $user = UserData::factory()->create(['user_type' => 4]);

    $this->actingAs($user)
        ->get(route('userdata.edit',$user))
        ->assertStatus(403);
}

    /** @test */
    public function admin_can_update_user()
    {
        $admin = $this->admin();
        $user = $this->teacher();

        $this->actingAs($admin)
            ->put(route('userdata.update', $user), [
                'FirstName' => 'Updated',
                'Surname' => 'Name',
                'UserEmail' => 'updated@example.com',
                'user_type' => 2,
            ])
            ->assertRedirect(route('EditUser'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('user_data', [
            'id' => $user->id,
            'FirstName' => 'Updated',
            'UserEmail' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function updating_user_requires_valid_data()
    {
        $admin = $this->admin();
        $user = $this->teacher();

        $this->actingAs($admin)
            ->put(route('userdata.update', $user), [])
            ->assertSessionHasErrors([
                'FirstName',
                'Surname',
                'UserEmail',
                'user_type',
            ]);
    }

    /** @test */
    public function updating_user_fails_if_email_taken_by_another_user()
    {
        $admin = $this->admin();
        $user = $this->teacher();
        $other = UserData::factory()->create(['UserEmail' => 'taken@example.com']);

        $this->actingAs($admin)
            ->put(route('userdata.update', $user), [
                'FirstName' => 'Test',
                'Surname' => 'User',
                'UserEmail' => 'taken@example.com',
                'user_type' => 4,
            ])
            ->assertSessionHasErrors('UserEmail');
    }

/** @test */
public function admin_can_delete_a_user()
{
    $admin = UserData::factory()->create(['user_type' => 2]);
    $user = UserData::factory()->create();

    $this->actingAs($admin)
        ->delete(route('userdata.delete', $user->id))
        ->assertRedirect(route('AdminControls'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('user_data', [
        'id' => $user->id,
    ]);
}


    /** @test */
    public function admin_can_search_for_users()
    {
        $admin = $this->admin();
        $user = UserData::factory()->create(['FirstName' => 'Alice']);

        $this->actingAs($admin)
            ->get(route('userdata.liveSearch', ['q' => 'Ali']))
            ->assertStatus(200)
            ->assertJsonFragment(['FirstName' => 'Alice']);
    }

    /** @test */
    public function empty_search_returns_empty_array()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('userdata.liveSearch', ['q' => '']))
            ->assertExactJson([]);
    }



    /** @test */
    public function admin_can_view_password_reset_form_for_any_user()
    {
        $admin = $this->admin();
        $user = $this->teacher();

        $this->actingAs($admin)
            ->get(route('ChangeUserPassword', $user->id))
            ->assertStatus(200)
            ->assertViewIs('AdminControls.changeUserPassword');
    }

    /** @test */
    public function admin_can_reset_any_users_password()
    {
        $admin = $this->admin();
        $user = $this->teacher();

        $this->actingAs($admin)
            ->post(route('ChangeUserPassword.submit'), [
                'user_id' => $user->id,
                'newpassword1' => 'newpass123',
                'newpassword2' => 'newpass123',
            ])
            ->assertRedirect(route('userdata.showAdminView', $user->id))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('newpass123', $user->fresh()->password));
    }

    /** @test */
    public function admin_password_reset_requires_valid_data()
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('ChangeUserPassword.submit'), [])
            ->assertSessionHasErrors(['user_id', 'newpassword1', 'newpassword2']);
    }


    /** @test */
    public function admin_can_view_full_user_profile()
    {
        $admin = $this->admin();
        $user = $this->teacher();

        $this->actingAs($admin)
            ->get(route('userdata.showAdminView', $user->id))
            ->assertStatus(200)
            ->assertViewIs('admincontrols.UserDetails')
            ->assertViewHas('user');
    }
}

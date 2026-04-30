<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserData;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiveSearchTest extends TestCase
{
    use RefreshDatabase;

public function test_non_admin_cannot_access_user_manager()
{
    // Create a Teacher/Standard user
    $user = User::factory()->create(['user_type' => 3]);

    // Attempt accessing as that user
    $response = $this->actingAs($user)->get('/user_manager');

    // Check standard user can't access 
    $response->assertStatus(403);
}

public function test_admin_can_access_user_manager()
{
    // Create an Admin user
    $user = User::factory()->create(['user_type' => 1]);

    $response = $this->actingAs($user)->get('/user_manager');

    // Check admin user can access user manager
    $response->assertStatus(200);
}

}
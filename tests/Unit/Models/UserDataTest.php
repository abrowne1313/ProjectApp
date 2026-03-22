<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\UserData;
use App\Models\ClassLists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserDataTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $user = new UserData();

        $this->assertEqualsCanonicalizing([
            'FirstName',
            'Surname',
            'UserEmail',
            'password',
            'user_type',
        ], $user->getFillable());
    }

    /** @test */
    public function password_is_hashed_when_set()
    {
        $user = UserData::factory()->create([
            'password' =>Hash::make('secret123'),
        ]);

        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    /** @test */
    public function it_can_have_classes()
    {
        $user = UserData::factory()->create();
        $class = ClassLists::factory()->create(['teacher_id' => $user->id]);

        $this->assertTrue($user->classes->contains($class));
    }

    /** @test */
    public function factory_creates_valid_user()
    {
        $user = UserData::factory()->create();

        $this->assertNotNull($user->id);
        $this->assertNotEmpty($user->FirstName);
        $this->assertNotEmpty($user->UserEmail);
    }
}

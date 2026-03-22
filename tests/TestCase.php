<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
   protected function setUp(): void
{
    parent::setUp();

    \DB::table('user_types')->insert([
        ['id' => 1, 'usertype' => 'AdminUser'],
        ['id' => 2, 'usertype' => 'CentreAdmin'],
        ['id' => 3, 'usertype' => 'HoDUser'],
        ['id' => 4, 'usertype' => 'teacher_user'],
    ]);
}

}

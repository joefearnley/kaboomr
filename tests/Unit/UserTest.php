<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_defaults_to_non_admin()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isAdmin());
    }

    public function test_user_can_be_admin()
    {
        $user = User::factory()->create([
            'is_admin' => 1
        ]);

        $this->assertTrue($user->isAdmin());
    }
}

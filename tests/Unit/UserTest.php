<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_defaults_to_active()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->isActive());
    }

    public function test_user_can_be_inactive()
    {
        $user = User::factory()->create([
            'is_active' => false
        ]);

        $this->assertFalse($user->isActive());
    }

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

    public function test_user_defaults_to_show_most_used_bookmarks()
    {
        $user = User::factory()->create();

        $this->assertTrue($user->showMostUsedTags());
    }

}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class NavTest extends TestCase
{
    use RefreshDatabase;

    public function test_nav_shows_logout_link_in_dropdown_nav()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertSee('Logout');
    }

    public function test_nav_shows_account_link_in_dropdown_nav()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertSee('Account');
    }

    public function test_nav_does_not_show_admin_link_in_dropdown_nav()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertDontSee('Admin');
    }

    public function test_nav_shows_admin_link_in_dropdown_nav_when_logged_is_as_admin()
    {
        $user = User::factory()->create([
            'is_admin' => 1
        ]);

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertSee('Admin');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_bookmarks_show_bookmark_list_with_no_bookmarks()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertStatus(200);

        $response->assertSee('You do not have any bookmarks yet!');
        $response->assertSee('Create One');
    }

    public function test_bookmarks_show_bookmark_list()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertStatus(200);

        $user->bookmarks->each(function ($bookmark) use ($response) {
            $response->assertSee($bookmark->name);
        });
    }

    public function test_cannot_access_bookmarks_list_without_being_logged_in()
    {
        $response = $this->get('/bookmarks');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}

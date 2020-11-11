<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_bookmarks_list_without_being_logged_in()
    {
        $response = $this->get('/bookmarks');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_bookmarks_show_bookmark_list_with_no_bookmarks()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertStatus(200);

        $response->assertSee('You do not have any bookmarks yet!');
        $response->assertSee('Create One');
    }

    public function test_bookmarks_shows_bookmark_list()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertStatus(200);

        $user->bookmarks->each(function ($bookmark) use ($response) {
            $response->assertSee($bookmark->name);
            $response->assertSee($bookmark->url);
            $response->assertSee($bookmark->description);
        });
    }

    public function test_bookmarks_shows_bookmark_list_with_tags()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();
        $bookmark->tag(['tag1', 'tag2']);

        $response = $this->actingAs($user)->get('/bookmarks');

        $response->assertStatus(200);

        $user->bookmarks->each(function ($bookmark) use ($response) {
            $response->assertSee($bookmark->name);
            $response->assertSee($bookmark->url);
            $response->assertSee($bookmark->description);
        });

        $response->assertSee('tag1');
        $response->assertSee('tag2');
    }

    public function test_authenticated_user_can_only_see_their_bookmarks()
    {
        $user1 = User::factory()
            ->hasBookmarks(2)
            ->create();

        $user2 = User::factory()
            ->hasBookmarks(1)
            ->create();

        $response = $this->actingAs($user1)->get('/bookmarks');

        $response->assertStatus(200);

        $user1->bookmarks->each(function ($bookmark) use ($response) {
            $response->assertSee($bookmark->name);
            $response->assertSee($bookmark->url);
            $response->assertSee($bookmark->description);
        });

        $user2->bookmarks->each(function ($bookmark) use ($response) {
            $response->assertDontSee($bookmark->name);
            $response->assertDontSee($bookmark->url);
            $response->assertDontSee($bookmark->description);
        });
    }
}

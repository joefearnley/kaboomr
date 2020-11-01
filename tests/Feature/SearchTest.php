<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bookmark;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_search_without_being_logged_in()
    {
        $searchTerm = 'test';

        $response = $this->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_search_returns_no_results()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $searchTerm = 'asfdasdfasdf';

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(200);
        $response->assertSee('No results found for "' . $searchTerm  .'".');
    }

    public function test_search_by_name_returns_results()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();


        $bookmark = $user->bookmarks->first();

        $searchTerm = $bookmark->name;

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(200);
        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
    }

    public function test_search_by_tag_returns_results()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $tags = ['tag1', 'tag2'];
        $bookmark->tag($tags);

        $searchTerm = $tags[0];

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $tags[0]);

        $response->assertStatus(200);
        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
    }
}

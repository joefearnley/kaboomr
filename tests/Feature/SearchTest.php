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
        $response->assertViewIs('bookmarks.search-results');
        $response->assertSee('No results found for "' . $searchTerm  .'".');
    }

    public function test_search_by_name_with_no_tags_returns_results()
    {
        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id,
            'name' => 'Guitar World',
            'url' => 'http://www.guitarworld.com',
            'description' => 'This is Guitar World.'
        ]);

        $searchTerm = 'World';

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.search-results');

        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
    }

    public function test_search_by_name_returns_results()
    {
        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id,
            'name' => 'Guitar World',
            'url' => 'http://www.guitarworld.com',
            'description' => 'This is Guitar World.'
        ]);

        $bookmark->tag(['Guitar', 'Guitar World']);

        $searchTerm = 'World';

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.search-results');

        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
    }

    public function test_search_by_tag_returns_results()
    {
        $user = User::factory()->create();

        $bookmark = Bookmark::factory()->create([
            'user_id' => $user->id,
            'name' => 'Guitar World',
            'url' => 'http://www.guitarworld.com',
            'description' => 'This is Guitar World'
        ]);

        $bookmark->tag(['Guitar', 'Guitar World', 'tag1']);

        $searchTerm = 'tag1';

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.search-results');

        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
    }

    public function test_search_by_tag_and_name_returns_results()
    {
        $user = User::factory()->create();

        $bookmark1 = Bookmark::factory()->create([
            'user_id' => $user->id,
            'name' => 'Guitar World',
            'url' => 'http://www.guitarworld.com',
            'description' => 'This is Guitar World'
        ]);

        $bookmark1->tag(['Guitar', 'Guitar World']);

        $bookmark2 = Bookmark::factory()->create([
            'user_id' => $user->id,
            'name' => 'Google',
            'url' => 'http://www.google.com',
            'description' => ''
        ]);

        $bookmark2->tag(['guitar']);

        $searchTerm = 'guitar';

        $response = $this->actingAs($user)->get('/bookmarks/search/' . $searchTerm);

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.search-results');

        $response->assertSee($bookmark1->name);
        $response->assertSee($bookmark1->url);
        $response->assertSee($bookmark1->description);

        $response->assertSee($bookmark2->name);
        $response->assertSee($bookmark2->url);
        $response->assertSee($bookmark2->description);
    }
}

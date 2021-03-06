<?php

namespace Tests\Feature\Bookmarks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bookmark;

class BookmarkTagTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_list_redirect_to_login_when_not_authenticated()
    {
        $response = $this->get(route('bookmarks.tag', 'tag1'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_tag_list_loads_nothing_with_no_tags()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $tag = 'tag1';

        $response = $this
            ->actingAs($user)
            ->get(route('bookmarks.tag', $tag));

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.taglist');
        $response->assertSee('No bookmarks tagged with "' . $tag . '".');
        $response->assertSee('Create Bookmark');
    }

    public function test_tag_list_loads()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();
        $tags = ['tag1', 'tag2'];
        $bookmark->tag($tags);

        $tag = $tags[0];

        $response = $this
            ->actingAs($user)
            ->get(route('bookmarks.tag', $tag));

        $response->assertStatus(200);
        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
        $response->assertSee('Bookmarks tagged with "'. $tag . '".');
        $response->assertSee(\Illuminate\Support\Str::title($tags[0]));
        $response->assertSee(\Illuminate\Support\Str::title($tags[1]));
    }
}

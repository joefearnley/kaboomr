<?php

namespace Tests\Feature;

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
        $response = $this->get('/bookmarks/tag/tag1');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_tag_list_loads_nothing_with_no_tags()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();
        
            $tag = 'tag1';

            $response = $this->actingAs($user)
                ->get('/bookmarks/tag/' . $tag);

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.taglist');
        $response->assertSee('No bookmarks found for "' . $tag . '".');
        $response->assertSee('Create One');
    }

    public function test_tag_list_loads()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();
        $tags = ['tag1', 'tag2'];
        $bookmark->tag($tags);

        $response = $this
            ->actingAs($user)
            ->get('/bookmarks/tag/' . $tags[0]);

        $response->assertStatus(200);
        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
        $response->assertSee(\Illuminate\Support\Str::title($tags[0]));
        $response->assertSee(\Illuminate\Support\Str::title($tags[1]));
    }
}

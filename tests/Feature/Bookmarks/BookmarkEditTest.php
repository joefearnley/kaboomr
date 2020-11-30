<?php

namespace Tests\Feature\Bookmarks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_edit_bookmark_that_user_does_not_own()
    {
        $user1 = User::factory()
            ->hasBookmarks(2)
            ->create();

        $user2 = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user2->bookmarks->first()->id;

        $response = $this
            ->actingAs($user1)
            ->get(route('bookmarks.edit', $bookmark));

        $response->assertStatus(403);
    }

    public function test_edit_page_loads()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();
        $tags = ['tag1', 'tag2'];
        $bookmark->tag($tags);

        $response = $this
            ->actingAs($user)
            ->get(route('bookmarks.edit', $bookmark));

        $response->assertStatus(200);
        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
        $response->assertSee(\Illuminate\Support\Str::title($tags[0]));
        $response->assertSee(\Illuminate\Support\Str::title($tags[1]));
    }

    public function test_cannot_update_bookmark_with_no_put_method()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $formData = [
            'name' => $bookmark->name,
            'url' => $bookmark->url,
            'description' => $bookmark->description,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(405);
    }

    public function test_cannot_update_bookmark_with_no_name()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $formData = [
            '_method' => 'PUT',
            'name' => '',
            'url' => $bookmark->url,
            'description' => $bookmark->description,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_cannot_update_bookmark_with_no_url()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $formData = [
            '_method' => 'PUT',
            'name' => $bookmark->name,
            'url' => '',
            'description' => $bookmark->description,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('url');
    }

    public function test_can_edit_bookmark()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $tags = ['tag1', 'tag2', 'tag3'];

        $formData = [
            '_method' => 'PUT',
            'name' => 'This is an updated bookmark',
            'url' => 'https://lmgtfy.app/',
            'description' => 'This is an updated description.',
            'tags' => implode(',', $tags)
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $bookmark->refresh();

        $this->assertEquals($formData['name'], $bookmark->name);
        $this->assertEquals($formData['url'], $bookmark->url);
        $this->assertEquals($formData['description'], $bookmark->description);

        $bookmarkTags = $bookmark->tagNames();
        $this->assertEquals(\Illuminate\Support\Str::title($tags[0]), $bookmarkTags[0]);
        $this->assertEquals(\Illuminate\Support\Str::title($tags[1]), $bookmarkTags[1]);
        $this->assertEquals(\Illuminate\Support\Str::title($tags[2]), $bookmarkTags[2]);
    }

    public function test_can_add_tags_to_bookmark()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $this->assertEquals(0, count($bookmark->tagNames()));

        $tags = ['tag1', 'tag2'];

        $formData = [
            '_method' => 'PUT',
            'name' => $bookmark->name,
            'url' => $bookmark->url,
            'description' => $bookmark->description,
            'tags' => implode(',', $tags)
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $bookmark->refresh();

        $this->assertEquals(2, count($bookmark->tagNames()));

        $this->assertDatabaseHas('tagging_tags', [
            'slug' => $tags[0],
            'name' => ucfirst($tags[0]),
        ]);

        $this->assertDatabaseHas('tagging_tags', [
            'slug' => $tags[1],
            'name' => ucfirst($tags[1]),
        ]);
    }

    public function test_can_remove_tags_from_bookmark()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $tags = ['delete_tag1', 'delete_tag2'];
        $bookmark->tag($tags);
        $bookmark->refresh();

        $this->assertEquals(2, count($bookmark->tagNames()));

        $formData = [
            '_method' => 'PUT',
            'name' => $bookmark->name,
            'url' => $bookmark->url,
            'description' => $bookmark->description,
            'tags' => ''
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $bookmark->refresh();

        $this->assertEquals(0, count($bookmark->tagNames()));

        $this->assertDatabaseMissing('tagging_tags', [
            'slug' => $tags[0],
            'name' => ucfirst($tags[0]),
        ]);

        $this->assertDatabaseMissing('tagging_tags', [
            'slug' => $tags[1],
            'name' => ucfirst($tags[1]),
        ]);
    }

    public function test_update_bookmark_shows_flash_message_on_success()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $formData = [
            '_method' => 'PUT',
            'name' => 'This is an updated bookmark',
            'url' => 'https://lmgtfy.app/',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.update', $bookmark), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $response->assertSessionHas([
            'success' => 'Bookmark successfully been updated!'
        ]);
    }
}

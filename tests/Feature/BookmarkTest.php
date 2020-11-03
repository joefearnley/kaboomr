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

    public function test_cannot_see_create_bookmark_form_when_not_logged_in()
    {
        $response = $this->get('/bookmarks/create');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_can_see_create_bookmark_form_when_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bookmarks/create');

        $response->assertStatus(200);
        $response->assertViewIs('bookmarks.create');
    }

    public function test_cannot_create_bookmark_with_no_data()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => '',
            'url' => '',
            'description' => '',
        ];

        $response = $this->actingAs($user)->post('/bookmarks/', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('url');
    }

    public function test_cannot_create_bookmark_with_no_name()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => '',
            'url' => 'http://www.google.com',
            'description' => '',
        ];

        $response = $this->actingAs($user)->post('/bookmarks/', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_cannot_create_bookmark_with_no_url()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => 'This is a link',
            'url' => '',
            'description' => '',
        ];

        $response = $this->actingAs($user)->post('/bookmarks/', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('url');
    }

    public function test_cannot_create_bookmark_with_invalid_url()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => 'This is a link',
            'url' => 'asfsadfasdfsa',
            'description' => '',
        ];

        $response = $this->actingAs($user)->post('/bookmarks/', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('url');
    }

    public function test_can_create_bookmark()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => 'Google',
            'url' => 'http://google.com',
            'description' => 'This is a description',
        ];

        $response = $this->actingAs($user)->post('/bookmarks', $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $this->assertDatabaseHas('bookmarks', $formData);
    }

    public function test_can_create_bookmark_with_tags()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => 'Google',
            'url' => 'http://google.com',
            'description' => 'This is a description',
            'tags' => 'google,web dev,description,test'
        ];

        $response = $this->actingAs($user)->post('/bookmarks', $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $user->id,
            'name' => 'Google',
            'url' => 'http://google.com',
            'description' => 'This is a description',
        ]);

        $this->assertDatabaseHas('tagging_tags', [
            'slug' => 'google',
            'name' => 'Google',
        ]);
    }

    public function test_cannot_edit_bookmark_that_user_does_not_own()
    {
        $user1 = User::factory()
            ->hasBookmarks(2)
            ->create();

        $user2 = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmarkToDEditId = $user2->bookmarks->first()->id;

        $response = $this
            ->actingAs($user1)
            ->get('/bookmarks/' . $bookmarkToDEditId .'/edit/');

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
            ->get('/bookmarks/' . $bookmark->id .'/edit/');

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
            ->post('/bookmarks/' . $bookmark->id, $formData);

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
            ->post('/bookmarks/' . $bookmark->id, $formData);

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
            ->post('/bookmarks/' . $bookmark->id, $formData);

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
            ->post('/bookmarks/' . $bookmark->id, $formData);

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
            ->post('/bookmarks/' . $bookmark->id, $formData);

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
            ->post('/bookmarks/' . $bookmark->id, $formData);

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

    public function test_cannot_delete_bookmark_that_user_does_not_own()
    {
        $user1 = User::factory()
            ->hasBookmarks(2)
            ->create();

        $user2 = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmarkToDeleteId = $user2->bookmarks->first()->id;

        $formData = [
            '_method' => 'Delete',
        ];

        $response = $this
            ->actingAs($user1)
            ->post('/bookmarks/' . $bookmarkToDeleteId, $formData);

        $response->assertStatus(403);
    }

    public function test_can_delete_bookmark()
    {
        $user = User::factory()
            ->hasBookmarks(1)
            ->create();

        $bookmark = $user->bookmarks->first();

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id
        ]);

        $formData = [
            '_method' => 'Delete',
        ];

        $response = $this
            ->actingAs($user)
            ->post('/bookmarks/' . $bookmark->id, $formData);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('bookmarks', [
            'id' => $bookmark->id
        ]);
    }
}

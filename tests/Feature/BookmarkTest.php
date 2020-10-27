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

        // $this->assertDatabaseHas('tagging_tags', [
        //     'user_id' => $user->id,
        //     'name' => 'Google',
        //     'url' => 'http://google.com',
        //     'description' => 'This is a description',
        // ]);
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

        $response = $this
            ->actingAs($user)
            ->get('/bookmarks/' . $bookmark->id .'/edit/');

        $response->assertStatus(200);
        $response->assertSee($bookmark->name);
        $response->assertSee($bookmark->url);
        $response->assertSee($bookmark->description);
    }

    public function test_cannot_update_bookmark_with_no__method()
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

        $formData = [
            '_method' => 'PUT',
            'name' => 'This is an updated bookmark',
            'url' => 'https://lmgtfy.app/',
            'description' => 'This is an updated description.',
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

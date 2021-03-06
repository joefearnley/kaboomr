<?php

namespace Tests\Feature\Bookmarks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_see_create_bookmark_form_when_not_logged_in()
    {
        $response = $this->get(route('bookmarks.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_can_see_create_bookmark_form_when_logged_in()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('bookmarks.create'));

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

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

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

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

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

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

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

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

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

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

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

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

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

    public function test_create_bookmark_shows_flash_message_on_success()
    {
        $user = User::factory()->create();

        $formData = [
            'name' => 'Google',
            'url' => 'http://google.com',
            'description' => 'This is a description',
            'tags' => 'google,web dev,description,test'
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('bookmarks.store'), $formData);

        $response->assertRedirect(route('bookmarks.index'));

        $response->assertSessionHas([
            'success' => 'Bookmark successfully been created!'
        ]);
    }
}

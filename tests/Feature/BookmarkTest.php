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
        });

        $user2->bookmarks->each(function ($bookmark) use ($response) {
            $response->assertDontSee($bookmark->name);
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
    }
}

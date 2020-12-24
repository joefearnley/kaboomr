<?php

namespace Tests\Feature\Bookmarks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkMostUsedTagsTest extends TestCase
{
    use RefreshDatabase;

    public function test_bookmark_list_does_not_shows_most_used_tags()
    {
        $user = User::factory([
            'show_most_used_tags' => false
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('users.edit', $user));

        $response->assertDontSee('Most used tags:');
    }

    public function test_bookmark_list_shows_most_used_tags()
    {
        $user = User::factory()
            ->hasBookmarks(4)
            ->create();

        $user->bookmarks->each(function($bookmark) {
            $bookmark->tag(['dev', 'webdev']);
        });

        $response = $this->actingAs($user)->get(route('bookmarks.index'));

        $response->assertStatus(200);
        $response->assertSee('Most used tags:');
    }
}

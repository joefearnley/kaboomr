<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkDeleteTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_delete_button_deletes_correct_bookmark()
    {
        $user = User::factory()
            ->hasBookmarks(3)
            ->create();

        $bookmark = $user->bookmarks->get(2);

        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
            'name' => $bookmark->name
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

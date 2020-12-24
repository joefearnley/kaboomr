<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkMostCommonTags extends TestCase
{

    public function test_bookmark_list_shows_most_used_tags()
    {
        $user = User::factory()
            ->hasBookmarks(4)
            ->create();

        // instead of above, create all bookmarks with 
        // same tags.....
        $user->bookmarks->each(function($bookmark) {
            $bookmark->tag(['dev', 'webdev']);
        });

        $response = $this->actingAs($user)->get(route('bookmarks.index'));

        $response->assertStatus(200);

        // check to see if 

        $response->assertSee('Most used tags');
    }
}

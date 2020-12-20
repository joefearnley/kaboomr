<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class BookmarkMostUsedTags extends TestCase
{

    public function test_bookmark_list_shows_most_used_tags()
    {
        $user = User::factory()
            ->hasBookmarks(4)
            ->create();
        
        // instead of above, create all bookmarks with 
        // same tags.....
    }
}

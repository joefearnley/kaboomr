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

    public function test_create_bookmark_with_tags()
    {
        // as a user
        // create a bookmark
        // add some tags

        // submit form

        // confirm redirect

        // see bookmark in DB
        // see tags in DB

        $this->assertEquals(true, true);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bookmark;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_search_without_being_logged_in()
    {
        $searchTerm = 'test';

        $response = $this->get('/search/' . $searchTerm);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays()
    {
        $response = $this->get(route('home'));

        $response->assertViewIs('home');

        $response->assertSee('Kaboomr - your bookmarks are here.');
        $response->assertSee('Log in');
        $response->assertSee('Sign up');
    }

    public function test_user_is_redirected_to_bookmarks_when_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));
    }
}

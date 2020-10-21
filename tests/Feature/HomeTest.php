<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_displays()
    {
        $response = $this->get('/');

        $response->assertViewIs('welcome');

        $response->assertSee('Kaboomr - your bookmarks are here.');
        $response->assertSee('Log in');
        $response->assertSee('Sign up');
    }
}

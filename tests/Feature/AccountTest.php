<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_main_account_page_redirects_to_login_when_not_authenticated()
    {
        $response = $this->get('/account');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    // public function test_main_account_page_load()
    // {

    // }

}

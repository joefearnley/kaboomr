<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    public function test_non_admin_user_cannot_access_user_admin_list()
    {
        $response = $this->get('/user-admin');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}

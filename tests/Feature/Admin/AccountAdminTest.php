<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccountAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_authenticated_non_admin_user_cannot_access_account_admin_list_and_is_redirected_to_login_page()
    {
        $response = $this->get('/admin/accounts');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_access_account_admin_list_and_is_redirected_to_bookmark_list()
    {
        $user = User::factory()
            ->hasBookmarks(2)
            ->create();

        $response = $this->actingAs($user)->get('/admin/accounts');

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_admin_user_can_access_user_admin_list()
    {
        $user = User::factory()->create([
            'is_admin' => 1
        ]);

        $response = $this->actingAs($user)->get('/admin/accounts');

        $response->assertStatus(200);
        $response->assertViewIs('admin.accounts.index');
    }
}

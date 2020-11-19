<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAccountAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_authenticated_non_admin_user_cannot_access_user_account_admin_list()
    {
        $response = $this->get('/admin/users');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_access_user_account_admin_list()
    {
        $user = User::factory()
            ->hasBookmarks(2)
            ->create();

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_admin_user_can_access_user_acount_admin_list()
    {
        $user = User::factory()->create([
            'is_admin' => 1
        ]);

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertSee('User Accounts');
    }

    public function test_admin_user_can_see_other_user_accounts()
    {
        $user = User::factory()->create([
            'is_admin' => 1
        ]);

        $nonAdminUsers = User::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(200);

        foreach($nonAdminUsers as $nonAdminUser) {
            $response->assertSee($nonAdminUser->name);
            $response->assertSee($nonAdminUser->email);
        }
    }
}

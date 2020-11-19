<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAccountAdminEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_edit_user_account_form_when_not_authenticated()
    {
        $nonAdminUser = User::factory()->create();

        $response = $this->get('/admin/users/' . $nonAdminUser->id . '/edit/');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_access_edit_user_account_form()
    {
        $nonAdminUser = User::factory()->create();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/admin/users/' . $nonAdminUser->id .'/edit/');

            $response->assertStatus(302);
            $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_admin_user_can_access_user_account_admin_list()
    {
        $nonAdminUser = User::factory()->create();

        $user = User::factory()->create([
            'is_admin' => 1
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/users/' . $nonAdminUser->id .'/edit/');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee($nonAdminUser->name);
        $response->assertSee($nonAdminUser->email);
    }
}

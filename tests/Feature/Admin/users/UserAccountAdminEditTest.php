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

        $response = $this->get('/admin/accounts/edit/' . $nonAdminUser->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }


    public function test_authenticated_non_admin_user_cannot_access_user_account_admin_list()
    {
        $nonAdminUsers = User::factory()->create();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/accounts/edit/' . $nonAdminUser->id);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));
    }
}

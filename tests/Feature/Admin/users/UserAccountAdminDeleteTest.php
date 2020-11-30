<?php

namespace Tests\Feature\Admin\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAccountAdminDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_edit_user_account_form_when_not_authenticated()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            '_method' => 'DELETE',
        ];

        $response = $this->post(route('users.destroy', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_delete_users()
    {
        $nonAdminUser = User::factory()->create();

        $user = User::factory()->create();
        $formData = [
            '_method' => 'DELETE',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('users.destroy', $user), $formData);

            $response->assertStatus(302);
            $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_can_delete_user()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            '_method' => 'DELETE',
        ];

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('users.destroy', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_delete_user_shows_flash_message_on_success()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            '_method' => 'DELETE',
        ];

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('users.destroy', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $response->assertSessionHas([
            'success' => 'User successfully deleted!'
        ]);
    }
}

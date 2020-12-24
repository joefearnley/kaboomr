<?php

namespace Tests\Feature\Admin\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAccountAdminEditTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_edit_user_account_form_when_not_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_access_edit_user_account_form()
    {
        $admin = User::factory()->create();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('users.edit', $user));

            $response->assertStatus(302);
            $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_admin_user_can_access_user_account_admin_list()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_cannot_update_user_account_with_no_put_method()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            'name' => $user->name,
            'email' => $user->email
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(405);
    }

    public function test_cannot_update_user_account_with_no_name()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            '_method' => 'PUT',
            'name' => '',
            'email' => $user->url,
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('name');
    }

    public function test_cannot_update_user_account_with_no_email()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            '_method' => 'PUT',
            'name' => $user->name,
            'email' => '',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('email');
    }

    public function test_can_edit_user()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $updatedUserName = 'John Doe';
        $updatedUserEmail = 'john.doe123@gmail.com';

        $formData = [
            '_method' => 'PUT',
            'name' => $updatedUserName,
            'email' => $updatedUserEmail,
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => $updatedUserName,
            'email' => $updatedUserEmail,
        ]);
    }

    public function test_update_user_shows_flash_message_on_success()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $updatedUserName = 'John Doe';
        $updatedUserEmail = 'john.doe123@gmail.com';

        $formData = [
            '_method' => 'PUT',
            'name' => $updatedUserName,
            'email' => $updatedUserEmail,
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas([
            'success' => 'User successfully updated!'
        ]);
    }

    public function test_edit_user_account_form_shows_admin_field()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee('Administrator?');
    }

    public function test_can_set_user_as_admin()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $formData = [
            '_method' => 'PUT',
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => 'on'
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => '1'
        ]);
    }

    public function test_edit_user_account_form_shows_active_field()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertSee('Active?');
    }

    public function test_can_activate_user()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create([
            'is_active' => 0
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => '0',
            'is_active' => '0',
        ]);

        $formData = [
            '_method' => 'PUT',
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => 'on',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => '0',
            'is_active' => '1',
        ]);
    }

    public function test_can_deactivate_user()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => '0',
            'is_active' => '1',
        ]);

        $formData = [
            '_method' => 'PUT',
            'name' => $user->name,
            'email' => $user->email,
            'is_active' => '',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => '0',
            'is_active' => '0',
        ]);
    }
}

<?php

namespace Tests\Feature\Admin\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAccountAdminCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_access_create_user_account_form_when_not_authenticated()
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.create', $user));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_access_create_user_account_form()
    {
        $admin = User::factory()->create();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('users.create', $user));

            $response->assertStatus(302);
            $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_admin_user_can_access_create_user_account_form()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
        $response->assertSee('Create User Account');
    }

    public function test_cannot_create_user_account_with_no_name()
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
            ->post(route('users.store', $user), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('name');
    }

    // public function test_cannot_update_user_account_with_no_email()
    // {
    //     $admin = User::factory()->create([
    //         'is_admin' => 1
    //     ]);

    //     $user = User::factory()->create();

    //     $formData = [
    //         '_method' => 'PUT',
    //         'name' => $user->name,
    //         'email' => '',
    //     ];

    //     $response = $this
    //         ->actingAs($admin)
    //         ->post(route('users.update', $user), $formData);

    //     $response->assertStatus(302);

    //    $response->assertSessionHasErrors('email');
    // }

    // public function test_can_edit_user()
    // {
    //     $admin = User::factory()->create([
    //         'is_admin' => 1
    //     ]);

    //     $user = User::factory()->create();

    //     $updatedUserName = 'John Doe';
    //     $updatedUserEmail = 'john.doe123@gmail.com';

    //     $formData = [
    //         '_method' => 'PUT',
    //         'name' => $updatedUserName,
    //         'email' => $updatedUserEmail,
    //     ];

    //     $response = $this
    //         ->actingAs($admin)
    //         ->post(route('users.update', $user), $formData);

    //     $response->assertStatus(302);

    //     $this->assertDatabaseHas('users', [
    //         'name' => $updatedUserName,
    //         'email' => $updatedUserEmail,
    //     ]);
    // }

    // public function test_update_user_shows_flash_message_on_success()
    // {
    //     $admin = User::factory()->create([
    //         'is_admin' => 1
    //     ]);

    //     $user = User::factory()->create();

    //     $updatedUserName = 'John Doe';
    //     $updatedUserEmail = 'john.doe123@gmail.com';

    //     $formData = [
    //         '_method' => 'PUT',
    //         'name' => $updatedUserName,
    //         'email' => $updatedUserEmail,
    //     ];

    //     $response = $this
    //         ->actingAs($admin)
    //         ->post(route('users.update', $user), $formData);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('users.index'));
    //     $response->assertSessionHas([
    //         'success' => 'User successfully updated!'
    //     ]);
    // }

}

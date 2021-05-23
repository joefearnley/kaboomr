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
        $response = $this->get(route('users.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_non_admin_user_cannot_access_create_user_account_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('users.create'));

            $response->assertStatus(302);
            $response->assertRedirect(route('bookmarks.index'));
    }

    public function test_admin_user_can_access_create_user_account_form()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

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

        $formData = [
            'name' => '',
            'email' => 'john.doe123@gmail.com',
            'password' => 'secret123',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('name');
    }

    public function test_cannot_create_user_account_with_no_email()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $formData = [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'secret123',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('email');
    }

    public function test_cannot_create_user_account_with_no_password()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'password' => '',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('password');
    }

    public function test_cannot_create_user_account_with_password_not_minimum_length()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'password' => '12334',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);

       $response->assertSessionHasErrors('password');
    }

    public function test_can_create_user_account()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'password' => 'secret123',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
        ]);
    }

    public function test_user_account_is_active_and_not_admin_as_default()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'password' => 'secret123',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'is_admin' => '0',
            'is_active' => '1',
        ]);
    }

    public function test_cannot_create_user_account_with_non_unique_email_addres()
    {
        $admin = User::factory()->create([
            'is_admin' => 1
        ]);

        $existingUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
        ]);

        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'password' => 'secret123',
            'is_active' => '1',
            'is_admin' => '0',
        ];

        $response = $this
            ->actingAs($admin)
            ->post(route('users.store'), $formData);

        $response->assertStatus(302);

        $response->assertSessionHasErrors('email');
    }

    // public function test_can_create_adnub_user_account()
    // {
    //     $admin = User::factory()->create([
    //         'is_admin' => 1
    //     ]);

    //     $formData = [
    //         'name' => 'John Doe',
    //         'email' => 'john.doe123@gmail.com',
    //         'password' => 'secret123',
    //         'is_admin' => '1',
    //         'is_active' => '1',
    //     ];

    //     $response = $this
    //         ->actingAs($admin)
    //         ->post(route('users.store'), $formData);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('users.index'));

    //     $this->assertDatabaseHas('users', [
    //         'name' => 'John Doe',
    //         'email' => 'john.doe123@gmail.com',
    //         'is_admin' => '0',
    //         'is_active' => '1',
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
    //         'name' => $updatedUserName,
    //         'email' => $updatedUserEmail,
    //     ];

    //     $response = $this
    //         ->actingAs($admin)
    //         ->post(route('users.store', $user), $formData);

    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('users.index'));
    //     $response->assertSessionHas([
    //         'success' => 'User Account successfully been created!'
    //     ]);
    // }

}

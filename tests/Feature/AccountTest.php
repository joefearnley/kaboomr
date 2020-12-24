<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_page_redirects_to_login_when_not_authenticated()
    {
        $response = $this->get(route('account'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_account_page_load()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('account'));

        $response->assertStatus(200);

        $response->assertSee('Edit User Account');
        $response->assertSee('Name');
        $response->assertSee('Email Address');
        $response->assertSee('Update');
        $response->assertSee('Reset Password');
    }

    public function test_updating_name_requires_form_fields()
    {
        $user = User::factory()->create();

        $formData = [
            '_method' => 'PATCH',
            'name' => '',
            'email' => $user->email,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_change_account_name()
    {
        $user = User::factory()->create();
        $updateName = 'John Doe';

        $formData = [
            '_method' => 'PATCH',
            'name' => $updateName,
            'email' => $user->email,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => $updateName,
        ]);
    }

    public function test_updating_name_shows_flash_message_on_success()
    {
        $user = User::factory()->create();
        $updateName = 'John Doe';

        $formData = [
            '_method' => 'PATCH',
            'name' => $updateName,
            'email' => $user->email,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => $updateName,
        ]);

        $response->assertSessionHas([
            'success' => 'Your user account has been updated!'
        ]);
    }

    public function test_updating_email_requires_form_fields()
    {
        $user = User::factory()->create();

        $formData = [
            '_method' => 'PATCH',
            'name' => $user->name,
            'email' => '',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_change_account_email()
    {
        $user = User::factory()->create();
        $updatedEmail = 'john.doe123@gmail.com';

        $formData = [
            '_method' => 'PATCH',
            'name' => $user->name,
            'email' => $updatedEmail,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));

        $this->assertDatabaseHas('users', [
            'email' => $updatedEmail,
        ]);
    }

    public function test_change_account_email_shows_flash_message_on_success()
    {
        $user = User::factory()->create();
        $updatedEmail = 'john.doe123@gmail.com';

        $formData = [
            '_method' => 'PATCH',
            'name' => $user->name,
            'email' => $updatedEmail,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));

        $this->assertDatabaseHas('users', [
            'email' => $updatedEmail,
        ]);

        $response->assertSessionHas([
            'success' => 'Your user account has been updated!'
        ]);
    }

    public function test_change_account_name_and_email_address()
    {
        $user = User::factory()->create();
        $updateName = 'John Doe';
        $updatedEmail = 'john.doe123@gmail.com';

        $formData = [
            '_method' => 'PATCH',
            'name' => $updateName,
            'email' => $user->email,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'name' => $updateName,
        ]);
    }


    public function test_user_edit_form_show_option_to_show_most_used_tags()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('account'));

        $response->assertSee('Show most used tags on Bookmark list?');
    }

    public function test_user_edit_form_remove_show_most_used_bookmarks()
    {
        $user = User::factory()->create();

        $formData = [
            '_method' => 'PATCH',
            'name' => $updateName,
            'email' => $user->email,
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'show_most_used_tags' => '0',
        ]);
    }
}

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

        $response->assertSee('Account');
        $response->assertSee('Update Name');
        $response->assertSee('Update Email Address');
        $response->assertSee('Reset Password');
    }


    public function test_updating_name_requires_form_fields()
    {
        $user = User::factory()->create();

        $formData = [
            '_method' => 'PATCH',
            'name' => '',
        ];

        $response = $this
            ->actingAs($user)
            ->post(route('account.update-name'), $formData);

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
        ];

        $response = $this->actingAs($user)
            ->post(route('account.update-name'), $formData);

        $response->assertStatus(302);

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
        ];

        $response = $this->actingAs($user)
            ->post(route('account.update-name'), $formData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => $updateName,
        ]);

        $response->assertSessionHas([
            'success' => 'Your account\'s Name has been updated!'
        ]);
    }

    public function test_updating_email_requires_form_fields()
    {
        $user = User::factory()->create();

        $formData = [
            '_method' => 'PATCH',
            'email' => '',
        ];

        $response = $this->actingAs($user)
            ->post(route('account.update-email'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_change_account_email()
    {
        $user = User::factory()->create();
        $updatedEmail = 'john.doe123@gmail.com';

        $formData = [
            '_method' => 'PATCH',
            'email' => $updatedEmail,
        ];

        $response = $this->actingAs($user)
            ->post(route('account.update-email'), $formData);

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
            'email' => $updatedEmail,
        ];

        $response = $this->actingAs($user)
            ->post(route('account.update-email'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));

        $this->assertDatabaseHas('users', [
            'email' => $updatedEmail,
        ]);

        $response->assertSessionHas([
            'success' => 'Your account\'s Email has been updated!'
        ]);
    }
}

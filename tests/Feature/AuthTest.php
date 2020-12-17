<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_displays_login_form()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_form_requires_email_and_password()
    {
        $formData = [
            'email' => '',
            'password' => '',
        ];

        $response = $this->post(route('login'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_form_requires_email()
    {
        $user = User::factory()->create();

        $formData = [
            'email' => $user->email,
            'password' => '',
        ];

        $response = $this->post(route('login'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    public function test_login_form_requires_password()
    {
        $user = User::factory()->create();

        $formData = [
            'email' => '',
            'password' => 'password',
        ];

        $response = $this->post(route('login'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_form_requires_user_to_be_active()
    {
        $user = User::factory()->create([
            'is_active' => false
        ]);

        $formData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post(route('login'), $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_form_redirects_to_bookmarks_page()
    {
        $user = User::factory()->create();

        $formData = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post(route('login'), $formData);

        $response->assertStatus(302);
        $response->assertRedirect(route('bookmarks.index'));

        $this->assertAuthenticatedAs($user);
    }
}

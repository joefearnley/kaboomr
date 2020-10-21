<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

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

        $response = $this->post('/login', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_form_requires_email()
    {
        $formData = [
            'email' => 'john.doe123@gmail.com',
            'password' => '',
        ];

        $response = $this->post('/login', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    public function test_login_form_requires_password()
    {
        $formData = [
            'email' => '',
            'password' => 'secret123',
        ];

        $response = $this->post('/login', $formData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }
}

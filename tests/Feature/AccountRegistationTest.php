<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccountRegistationTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_registration_defaults_email_verification_field_to_null()
    {
        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->post(route('register'), $formData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe123@gmail.com',
            'email_verified_at' => null,
        ]);
    }

    public function test_account_needs_to_verify_to_see_bookmarks_page()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('bookmarks.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_account_needs_to_verify_to_see_account_page()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('account'));

        $response->assertStatus(302);
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_account_needs_to_verify_to_see_admin_page()
    {
        $admin = User::factory()->create([
            'is_admin' => 1,
            'email_verified_at' => null
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('users.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('verification.notice'));
    }
}

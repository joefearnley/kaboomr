<?php

namespace Tests\Feature\Mail;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\UserAccountUpdate;

class UserAccountUpdateEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_update_email_is_sent()
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

        Mail::fake();

        $response = $this
            ->actingAs($admin)
            ->post(route('users.update', $user), $formData);

        Mail::assertSent(UserAccountUpdate::class, function ($mail) use ($user) {
            $this->assertTrue($mail->hasTo($user->email));
        });
    }
}

<?php

namespace Tests\Feature\Mail;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class UserAccountUpdateEmailTest extends TestCase
{
    public function test_email_is_sent()
    {
        $user = User::factory()->create();
        $updateName = 'John Doe';

        $formData = [
            '_method' => 'PATCH',
            'name' => $updateName,
        ];

        $response = $this
            ->actingAs($user)
            ->post('/account/update-name', $formData);

        Mail::fake();

        Mail::assertSent(UserUpdateNotification::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}

<?php

namespace Tests\Feature\Mail;



use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\UserAccountRegistrationNotification;

class UserAccountRegistrationNoficationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_update_email_is_sent()
    {
        $user = User::factory()->create();

        Mail::fake();

        Mail::to($user->email)->send(new UserAccountRegistrationNotification($user));

        Mail::assertSent(UserAccountRegistrationNotification::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}

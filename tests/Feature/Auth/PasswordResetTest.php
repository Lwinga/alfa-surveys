<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_password_reset_link_page_can_be_rendered()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_password_reset_link_can_be_requested()
    {
        $user = User::factory()->create();
        
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class);
        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', __(Password::RESET_LINK_SENT))
            ->assertRedirect();
    }

    public function test_reset_password_page_can_be_rendered()
    {
        $user = User::factory()->create();
        
        Notification::fake();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get(route('password.reset', [
                'token' => $notification->token,
            ], absolute: false));

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_users_can_reset_password()
    {
        $user = User::factory()->create();

        Notification::fake();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertSessionHas('status', __(Password::PASSWORD_RESET))
                ->assertRedirect('/login');

            return true;
        });
    }

    public function test_users_can_not_reset_password_with_invalid_token()
    {
        $user = User::factory()->create();
        
        Notification::fake();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => 'wrong-token',
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response
                ->assertSessionHasErrors(['email' => __(Password::INVALID_TOKEN)])
                ->assertRedirect();

            return true;
        });
    }
}

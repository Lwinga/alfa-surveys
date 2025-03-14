<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_email_verification_notice_page_can_be_rendered()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice', absolute:false));

        $response->assertStatus(200);
    }

    public function test_email_verification_notice_page_can_not_be_rendered_if_verified()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('verification.notice', absolute: false));

        $response->assertRedirect('/dashboard');
    }

    public function test_email_verification_link_can_be_sent()
    {
        $user = User::factory()->unverified()->create();

        Notification::fake();

        $response = $this->actingAs($user)->post(route('verification.send', absolute: false));

        Notification::assertSentTo($user, VerifyEmail::class);
        $response
            ->assertSessionHas('status')
            ->assertRedirect();
    }

    public function test_authenticated_users_can_verify_email()
    {
        $user = User::factory()->unverified()->create();
        
        Event::fake();

        $response = $this->actingAs($user)->get(URL::temporarySignedRoute(
            'verification.verify',
            now()->addHour(),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        ));

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->refresh()->hasVerifiedEmail());
        $response
            ->assertSessionHas('verified', true)
            ->assertRedirect('/dashboard');
    }

    public function test_authenticated_users_can_not_verify_email_with_invalid_hash()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(URL::temporarySignedRoute(
            'verification.verify',
            now()->addHour(),
            ['id' => $user->id, 'hash' => sha1('wrong-hash')],
            absolute: false
        ));

        $this->assertFalse($user->refresh()->hasVerifiedEmail());
        $response->assertStatus(403);
    }
}

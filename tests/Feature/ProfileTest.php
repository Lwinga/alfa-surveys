<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_visit_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_authenticated_users_can_update_profile_photo()
    {
        $user = User::factory()->create();

        Storage::fake();

        $photo = UploadedFile::fake()->image('photo.png', 256, 256);

        $response = $this->actingAs($user)->post('/user/profile-photo', [
            'photo' => $photo,
        ]);

        $user->refresh();
        Storage::assertExists($user->photo_path);
        $this->assertNotNull($user->photo_path);
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_authenticated_users_can_delete_profile_photo()
    {
        $user = User::factory()->create();

        Storage::fake();

        $photo = UploadedFile::fake()->image('photo.png', 256, 256);

        $this->actingAs($user)->post('/user/profile-photo', [
            'photo' => $photo,
        ]);

        $response = $this->actingAs($user)->delete('/user/profile-photo');

        $user->refresh();
        Storage::assertMissing($user->photo_path);
        $this->assertNull($user->photo_path);
        $response->assertRedirect();
    }

    public function test_authenticated_users_can_update_profile_information()
    {
        $user = User::factory()->create();
        $newName = 'Test Name';
        $newEmail = 'test@example.com';

        $response = $this->actingAs($user)->put('/user/profile-information', [
            'name' => $newName,
            'email' => $newEmail,
        ]);

        $user->refresh();
        $this->assertEquals($user->name, $newName);
        $this->assertEquals($user->email, $newEmail);
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_email_verificatin_link_is_sent_after_email_has_been_updated()
    {
        $user = User::factory()->create();
        $newEmail = 'test'.$user->email;

        Notification::fake();

        $response = $this->actingAs($user)->put('/user/profile-information', [
            'name' => $user->name,
            'email' => $newEmail,
        ]);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_authenticated_users_can_update_password()
    {
        $user = User::factory()->create();
        $newPassword = 'new-password';

        $response = $this->actingAs($user)->put('/user/password', [
            'current_password' => 'password',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();
    }

    public function test_authenticated_users_can_not_update_password_with_invalid_current_password()
    {
        $user = User::factory()->create();
        $newPassword = 'new-password';

        $response = $this->actingAs($user)->put('/user/password', [
            'current_password' => 'wrong-password',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $this->assertFalse(Hash::check($newPassword, $user->refresh()->password));
        $response
            ->assertSessionHasErrors()
            ->assertRedirect();
    }
}

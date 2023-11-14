<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\User\Models\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'phone' => '+79999999999',
            'email' => 'test@example.com',
            'password' => 'password',
            'terms' => 'yes',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_new_users_has_default_campaign(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'phone' => '+79999999999',
            'email' => 'test@example.com',
            'password' => 'password',
            'terms' => 'yes',
        ]);

        /** @var User $user */
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertSame($user->ownCampaigns()->count(), 1);
    }
}

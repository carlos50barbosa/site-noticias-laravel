<?php

namespace Tests\Feature\Auth;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_renders(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_authenticate(): void
    {
        $user = User::factory()->create([
            'role' => Role::ADMIN,
            'password' => 'secret-123',
        ]);

        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'secret-123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create(['password' => 'secret-123']);

        $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'errado',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/logout')->assertRedirect('/admin/login');

        $this->assertGuest();
    }

    public function test_login_is_rate_limited_to_five_attempts(): void
    {
        $user = User::factory()->create(['password' => 'secret-123']);

        foreach (range(1, 5) as $i) {
            $this->post('/admin/login', ['email' => $user->email, 'password' => 'errado']);
        }

        // 6ª tentativa: bloqueada por rate limit (mensagem de erro em 'email').
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret-123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_password_reset_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        $this->post('/admin/esqueci', ['email' => $user->email])
            ->assertSessionHas('status');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/admin/esqueci', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
            $response = $this->post('/admin/redefinir', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'nova-senha-123',
                'password_confirmation' => 'nova-senha-123',
            ]);

            $response->assertRedirect(route('login'));

            return true;
        });
    }
}

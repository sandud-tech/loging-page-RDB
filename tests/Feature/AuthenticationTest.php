<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authentication_pages_are_public(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/admin/login')->assertOk();
    }

    public function test_registration_creates_an_active_user_and_ignores_role_fields(): void
    {
        $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'StrongPass1!',
            'password_confirmation' => 'StrongPass1!',
            'role' => 'admin',
            'status' => 'inactive',
        ])->assertRedirect(route('login'));

        $user = User::where('email', 'new@example.com')->firstOrFail();

        $this->assertSame('user', $user->role);
        $this->assertSame('active', $user->status);
        $this->assertTrue(Hash::check('StrongPass1!', $user->password));
    }

    public function test_registration_rejects_duplicate_email_and_weak_passwords(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $this->from('/register')->post('/register', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'weak',
            'password_confirmation' => 'different',
        ])->assertRedirect('/register')->assertSessionHasErrors([
            'email',
            'password',
        ]);
    }

    public function test_a_user_can_login_but_cannot_open_the_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass1!'),
        ]);

        $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'StrongPass1!',
        ])->assertRedirect(route('users.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get('/dashboard')->assertOk();
        $this->get('/admin/dashboard')->assertRedirect(route('users.dashboard'));
    }

    public function test_a_user_cannot_login_through_the_admin_login_page(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('StrongPass1!'),
        ]);

        $this->from('/admin/login')->post('/admin/login', [
            'email' => 'user@example.com',
            'password' => 'StrongPass1!',
        ])->assertRedirect('/admin/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_an_inactive_account_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('StrongPass1!'),
        ]);
        $user->status = 'inactive';
        $user->save();

        $this->from('/login')->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'StrongPass1!',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_an_admin_can_open_only_the_admin_dashboard_and_sees_real_user_counts(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongPass1!'),
        ]);
        $admin->role = 'admin';
        $admin->save();

        User::factory()->count(2)->create();
        $inactiveUser = User::factory()->create();
        $inactiveUser->status = 'inactive';
        $inactiveUser->save();

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'StrongPass1!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('3')
            ->assertSee('2');
        $this->get('/dashboard')->assertRedirect(route('admin.dashboard'));
    }

    public function test_logout_invalidates_authentication_and_protected_pages(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_login_attempts_are_rate_limited(): void
    {
        RateLimiter::clear('127.0.0.1');

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', [
                'email' => 'missing@example.com',
                'password' => 'WrongPass1!',
            ])->assertSessionHasErrors('email');
        }

        $this->post('/login', [
            'email' => 'missing@example.com',
            'password' => 'WrongPass1!',
        ])->assertTooManyRequests();
    }
}

<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
    }

    public function test_user_can_view_register_page(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
    }

    public function test_customer_can_register_and_role_cannot_be_injected(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Fauzi Pelanggan',
            'phone' => '081234567890',
            'email' => 'fauzi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
            'role' => 'super_admin',
            'status' => 'suspended',
        ]);

        $response->assertRedirect(route('user.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'fauzi@example.com',
            'role' => UserRole::Customer->value,
            'status' => UserStatus::Active->value,
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals(UserRole::Customer, Auth::user()->role);
    }

    public function test_customer_registration_fails_with_invalid_indonesian_phone(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Invalid Phone',
            'phone' => '123456',
            'email' => 'invalidphone@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseMissing('users', ['email' => 'invalidphone@example.com']);
    }

    public function test_customer_registration_fails_without_terms(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'No Terms',
            'phone' => '081234567890',
            'email' => 'noterms@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('terms');
    }

    public function test_login_customer_redirects_to_intended_or_customer_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => 'password123',
            'role' => UserRole::Customer,
            'status' => UserStatus::Active,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_operator_redirects_to_operator_dashboard(): void
    {
        $venue = Venue::factory()->create();
        $operator = User::factory()->operator()->create([
            'email' => 'operator@example.com',
            'password' => 'password123',
            'venue_id' => $venue->id,
            'status' => UserStatus::Active,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'operator@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('operator.dashboard'));
        $this->assertAuthenticatedAs($operator);
    }

    public function test_login_super_admin_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->superAdmin()->create([
            'email' => 'admin@example.com',
            'password' => 'password123',
            'status' => UserStatus::Active,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_suspended_user_cannot_login(): void
    {
        $suspendedUser = User::factory()->suspended()->create([
            'email' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), [
                'email' => 'wrong@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        $response = $this->post(route('login'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('Terlalu banyak percobaan masuk', $errors[0]);
    }

    public function test_guest_redirected_to_login_on_protected_routes(): void
    {
        $this->get(route('user.dashboard'))->assertRedirect(route('login'));
        $this->get(route('operator.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_operator_or_admin_routes_receives_403(): void
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $this->actingAs($customer)->get(route('operator.dashboard'))->assertStatus(403);
        $this->actingAs($customer)->get(route('admin.dashboard'))->assertStatus(403);
    }

    public function test_operator_cannot_access_admin_routes_receives_403(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->get(route('admin.dashboard'))->assertStatus(403);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('beranda'));
        $this->assertGuest();
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_success_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('User#1234'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@gmail.com',
            'password' => 'User#1234'
        ]);

        $response->assertRedirect(route('landingpage'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('User#1234')
        ]);

        $response = $this->post('/login', [
            'email' => 'user@gmail.com',
            'password' => 'SalahBanget!'
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    /** @test */
    public function login_fails_when_email_not_registered()
    {
        $response = $this->post('/login', [
            'email' => 'tidakada@gmail.com',
            'password' => 'Password123!'
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    /** @test */
    public function login_fails_if_fields_empty()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertSessionHas([
            'message' => 'The email field is required.',
            'status' => 'error'
        ]);
        $this->assertGuest();
    }

    /** @test */
    public function login_redirects_admin()
    {
        $admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin#1234'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'Admin#1234'
        ]);

        $response->assertRedirect('/admin-dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function login_redirects_seller()
    {
        $seller = User::factory()->create([
            'email' => 'seller@gmail.com',
            'password' => Hash::make('Seller#1234'),
            'role' => 'seller',
        ]);

        $response = $this->post('/login', [
            'email' => 'seller@gmail.com',
            'password' => 'Seller#1234'
        ]);

        $response->assertRedirect('/seller-dashboard');
        $this->assertAuthenticatedAs($seller);
    }

    /** @test */
    public function remember_me_works()
    {
        $user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('User#1234'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@gmail.com',
            'password' => 'User#1234',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('landingpage'));
        $this->assertAuthenticatedAs($user);

        $this->assertNotEmpty(cookie()->getQueuedCookies());
    }

}

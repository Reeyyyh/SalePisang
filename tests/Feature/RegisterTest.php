<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_success_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Testing User',
            'email' => 'test@gmail.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@gmail.com',
        ]);
    }

    /** @test */
    public function register_fails_if_email_not_gmail()
    {
        $response = $this->post('/register', [
            'name' => 'User',
            'email' => 'user@yahoo.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString(
            'Email harus menggunakan domain @gmail.com',
            session('errors')->get('email')[0]
        );
    }

    /** @test */
    public function register_fails_if_email_already_exists()
    {
        User::factory()->create([
            'email' => 'user@gmail.com'
        ]);

        $response = $this->post('/register', [
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertStringContainsString(
            'The email has already been taken.',
            session('errors')->get('email')[0]
        );
    }

    /** @test */
    public function register_fails_if_password_too_short()
    {
        $response = $this->post('/register', [
            'name' => 'User',
            'email' => 'short@gmail.com',
            'password' => 'Ab1@',
            'password_confirmation' => 'Ab1@',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertStringContainsString(
            'The password field must be at least 8 characters.',
            session('errors')->get('password')[0]
        );
    }

    /** @test */
    public function register_fails_if_password_not_meet_regex()
    {
        $response = $this->post('/register', [
            'name' => 'User',
            'email' => 'abc@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertStringContainsString(
            'Password harus mengandung huruf besar, angka, simbol, serta minimal 8 karakter.',
            session('errors')->get('password')[0]
        );
    }

    /** @test */
    public function register_fails_if_password_confirmation_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'User',
            'email' => 'abc@gmail.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@12',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertStringContainsString(
            'The password confirmation does not match.',
            session('errors')->get('password')[0]
        );
    }

    /** @test */
    public function test_register_fails_if_email_too_long()
    {
        $response = $this->post('/register', [
            'name' => 'User Test',
            'email' => str_repeat('a', 101) . '@gmail.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_register_fails_if_name_too_long()
    {
        $response = $this->post('/register', [
            'name' => str_repeat('a', 101),
            'email' => 'user@gmail.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors(['name']);
    }
}

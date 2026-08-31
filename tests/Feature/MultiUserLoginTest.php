<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiUserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_from_shared_login_page(): void
    {
        $user = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'number' => '0170000000',
            'password' => bcrypt('secret123'),
        ]);

        $this->get('/login?role=user')->assertOk()->assertSee('User')->assertSee('Admin');

        $this->post('/login', [
            'email' => 'student@example.com',
            'password' => 'secret123',
            'role' => 'user',
        ])->assertRedirect('/dashboard');

        $this->assertTrue(session('user_logged_in'));
        $this->assertSame('user', session('auth_role'));
        $this->assertEquals($user->id, session('user_id'));
    }

    public function test_admin_can_login_from_shared_login_page(): void
    {
        $admin = Admin::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'number' => '0190000000',
            'password' => bcrypt('secret123'),
        ]);

        $this->get('/login?role=admin')->assertOk()->assertSee('Admin login portal');

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'role' => 'admin',
        ])->assertRedirect('/admin/dashboard');

        $this->assertTrue(session('admin_logged_in'));
        $this->assertSame('admin', session('auth_role'));
        $this->assertEquals($admin->id, session('admin_id'));
    }

    public function test_default_admin_account_is_created_when_missing(): void
    {
        Admin::query()->delete();

        $this->post('/login', [
            'email' => 'admin@exam.com',
            'password' => 'password123',
            'role' => 'admin',
        ])->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('admin', ['email' => 'admin@exam.com']);
        $this->assertTrue(session('admin_logged_in'));
    }
}

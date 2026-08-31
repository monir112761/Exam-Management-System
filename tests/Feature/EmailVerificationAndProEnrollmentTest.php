<?php

namespace Tests\Feature;

use App\Models\AccessType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationAndProEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_cannot_login(): void
    {
        $user = User::create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'number' => '0171111111',
            'password' => bcrypt('secret123'),
            'email_verification_token' => 'token-123',
            'email_verified_at' => null,
        ]);

        $this->from('/login')->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'secret123',
            'role' => 'user',
        ])->assertRedirect('/login');

        $this->assertNull(session('user_logged_in'));
        $this->assertSame($user->email, 'unverified@example.com');
    }

    public function test_verified_user_can_enroll_for_pro_access(): void
    {
        Mail::fake();

        $accessType = AccessType::firstOrCreate(
            ['code' => 'ST-1'],
            ['name' => 'ST-1', 'description' => 'Pro access plan', 'fee' => 500, 'is_active' => true]
        );

        $user = User::create([
            'name' => 'Pro User',
            'email' => 'pro@example.com',
            'number' => '0172222222',
            'password' => bcrypt('secret123'),
            'email_verification_token' => 'token-pro',
            'email_verified_at' => now(),
            'access_type_id' => AccessType::where('code', 'FREE')->first()?->id,
        ]);

        $this->withSession([
            'user_logged_in' => true,
            'user_id' => $user->id,
            'user_name' => $user->name,
        ])->post('/pro/enroll', [
            'amount' => '500',
            'transaction_id' => 'B-KASH-001',
        ])->assertRedirect('/dashboard');

        $user->refresh();
        $this->assertSame($accessType->id, $user->access_type_id);
    }
}

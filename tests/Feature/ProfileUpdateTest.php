<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_extended_profile_fields(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'number' => '1234567890',
            'password' => bcrypt('secret123'),
        ]);

        $this->withSession([
            'user_logged_in' => true,
            'user_id' => $user->id,
            'user_name' => $user->name,
        ])->post('/profile/update', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'number' => '9876543210',
            'address' => 'Dhaka, Bangladesh',
            'city' => 'Dhaka',
            'state' => 'Dhaka Division',
            'country' => 'Bangladesh',
            'gender' => 'male',
            'date_of_birth' => '2000-01-15',
            'bio' => 'Learning and exploring new technologies.',
        ])->assertRedirect();

        $user->refresh();

        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertSame('9876543210', $user->number);
        $this->assertSame('Dhaka, Bangladesh', $user->address);
        $this->assertSame('Dhaka', $user->city);
        $this->assertSame('Dhaka Division', $user->state);
        $this->assertSame('Bangladesh', $user->country);
        $this->assertSame('male', $user->gender);
        $this->assertSame('2000-01-15', $user->date_of_birth->format('Y-m-d'));
        $this->assertSame('Learning and exploring new technologies.', $user->bio);
    }
}

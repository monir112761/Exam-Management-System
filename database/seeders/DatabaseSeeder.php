<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        Admin::firstOrCreate(
            ['email' => 'monir112761@gmail.com'],
            [
                'name' => 'Moniruzzaman Monir',
                'number' => '0190000000',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Create Regular User
        User::create([
            'name' => 'Moniruzzaman Monir',
            'email' => 'student@gmail.com',
            'number' => '01784910673',
            'password' => Hash::make('password123'),
            //   'role'     => 'user', // or 'is_admin' => 0
        ]);
    }
}

// namespace Database\Seeders;

// use App\Models\User;
// // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class DatabaseSeeder extends Seeder
// {
//     /**
//      * Seed the application's database.
//      */
//     public function run(): void
//     {
//         // User::factory(10)->create();

//         User::factory()->create([
//             'name' => 'Moniruzzaman Monir',
//             'email' => 'monir1127@gmail.com',
//             'password' => bcrypt('Password'),
//         ]);
//     }
// }

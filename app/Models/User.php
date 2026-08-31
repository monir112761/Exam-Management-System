<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'email_verification_token',
        'number',
        'address',
        'city',
        'state',
        'country',
        'gender',
        'date_of_birth',
        'bio',
        'password',
        'access_type_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'email_verified_at' => 'datetime',
    ];

    public function accessType(): BelongsTo
    {
        return $this->belongsTo(AccessType::class, 'access_type_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasPermission(string $permissionName): bool
    {
        $permission = Permission::where('name', $permissionName)->first();
        if (! $permission) {
            return false;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($query) => $query->where('permissions.id', $permission->id))
            ->exists();
    }

    public function canAccessExam(Exam $exam): bool
    {
        if (! $this->accessType) {
            $defaultType = AccessType::where('code', 'FREE')->first();
            if ($defaultType) {
                $this->access_type_id = $defaultType->id;
                $this->save();
            }
        }

        if ($this->hasRole('Super Admin')) {
            return true;
        }

        if ($exam->accessTypes()->count() === 0) {
            return true;
        }

        $userAccessTypeId = $this->access_type_id;
        if (! $userAccessTypeId) {
            return false;
        }

        return $exam->accessTypes()->where('access_types.id', $userAccessTypeId)->exists();
    }

    public function hasVerifiedEmail(): bool
    {
        if (! empty($this->email_verified_at)) {
            return true;
        }

        return empty($this->email_verification_token);
    }
}

// namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;

// class DatabaseSeeder extends Seeder
// {
//     /**
//      * Seed the application's database.
//      */
//     public function run(): void
//     {
//         // 1. Create Admin User
//         User::create([
//             'name'     => 'Admin Monir',
//             'email'    => 'monir112761@gmail.com',
//             'number'   => '01784910673',
//             'password' => Hash::make('password123'),
//  //           'role'     => 'admin', // or 'is_admin' => 1 (if you have a role column)
//         ]);

//         // 2. Create Regular User
//         User::create([
//             'name'     => 'Moniruzzaman Monir',
//             'email'    => 'student@gmail.com',
//             'number'   => '01800000000',
//             'password' => Hash::make('password123'),
//  //           'role'     => 'user', // or 'is_admin' => 0
//         ]);
//     }
// }

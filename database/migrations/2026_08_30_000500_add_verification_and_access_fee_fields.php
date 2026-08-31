<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('access_types', function (Blueprint $table) {
            if (! Schema::hasColumn('access_types', 'fee')) {
                $table->decimal('fee', 10, 2)->default(0)->after('description');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'email_verification_token')) {
                $table->string('email_verification_token')->nullable()->after('email_verified_at');
            }
        });

        $free = DB::table('access_types')->where('code', 'FREE')->first();
        if (! $free) {
            $freeId = DB::table('access_types')->insertGetId([
                'name' => 'FREE',
                'code' => 'FREE',
                'description' => 'Default free access type',
                'fee' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $freeId = $free->id;
        }

        DB::table('users')->whereNull('access_type_id')->update(['access_type_id' => $freeId]);

        $defaultPlans = [
            ['name' => 'ST-1', 'code' => 'ST-1', 'fee' => 100, 'description' => 'Starter access plan'],
            ['name' => 'SR-2', 'code' => 'SR-2', 'fee' => 300, 'description' => 'Silver recommended plan'],
            ['name' => 'ST-3', 'code' => 'ST-3', 'fee' => 500, 'description' => 'Premium access plan'],
        ];

        foreach ($defaultPlans as $plan) {
            $existing = DB::table('access_types')->where('code', $plan['code'])->first();

            if (! $existing) {
                DB::table('access_types')->insert([
                    'name' => $plan['name'],
                    'code' => $plan['code'],
                    'description' => $plan['description'],
                    'fee' => $plan['fee'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('access_types')->where('id', $existing->id)->update([
                    'name' => $plan['name'],
                    'description' => $plan['description'],
                    'fee' => $plan['fee'],
                    'is_active' => true,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('access_types', function (Blueprint $table) {
            if (Schema::hasColumn('access_types', 'fee')) {
                $table->dropColumn('fee');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'email_verification_token')) {
                $table->dropColumn('email_verification_token');
            }
        });
    }
};

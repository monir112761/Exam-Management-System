<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('access_types')) {
            Schema::create('access_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('label')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('label')->nullable();
                $table->string('group_name')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['role_id', 'permission_id']);
            });
        }

        if (! Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'role_id']);
            });
        }

        if (! Schema::hasTable('exam_access_types')) {
            Schema::create('exam_access_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
                $table->foreignId('access_type_id')->constrained('access_types')->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['exam_id', 'access_type_id']);
            });
        }

        if (! Schema::hasTable('exam_questions')) {
            Schema::create('exam_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
                $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
                $table->integer('marks')->default(1);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->unique(['exam_id', 'question_id']);
            });
        }

        if (! Schema::hasTable('exam_attempts')) {
            Schema::create('exam_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
                $table->dateTime('started_at')->nullable();
                $table->dateTime('ended_at')->nullable();
                $table->dateTime('submitted_at')->nullable();
                $table->integer('duration_minutes')->nullable();
                $table->string('status')->default('in_progress');
                $table->json('data')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('exam_answers')) {
            Schema::create('exam_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
                $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
                $table->string('selected_option')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('obtained_marks')->default(0);
                $table->boolean('is_flagged')->default(false);
                $table->timestamps();
                $table->unique(['exam_attempt_id', 'question_id']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'access_type_id')) {
                $table->foreignId('access_type_id')->nullable()->after('number')->constrained('access_types')->nullOnDelete();
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (! Schema::hasColumn('exams', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable()->after('duration');
            }
            if (! Schema::hasColumn('exams', 'scheduled_at')) {
                $table->dateTime('scheduled_at')->nullable()->after('duration_minutes');
            }
            if (! Schema::hasColumn('exams', 'starts_at')) {
                $table->dateTime('starts_at')->nullable()->after('scheduled_at');
            }
            if (! Schema::hasColumn('exams', 'ends_at')) {
                $table->dateTime('ends_at')->nullable()->after('starts_at');
            }
            if (! Schema::hasColumn('exams', 'published_at')) {
                $table->dateTime('published_at')->nullable()->after('ends_at');
            }
            if (! Schema::hasColumn('exams', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('published_at');
            }
        });

        Schema::table('questions', function (Blueprint $table) {
            if (! Schema::hasColumn('questions', 'marks')) {
                $table->integer('marks')->default(1);
            }
        });

        Schema::table('results', function (Blueprint $table) {
            if (! Schema::hasColumn('results', 'total_marks')) {
                $table->integer('total_marks')->default(0)->after('score');
            }
            if (! Schema::hasColumn('results', 'obtained_marks')) {
                $table->integer('obtained_marks')->default(0)->after('total_marks');
            }
            if (! Schema::hasColumn('results', 'percentage')) {
                $table->decimal('percentage', 8, 2)->default(0)->after('obtained_marks');
            }
            if (! Schema::hasColumn('results', 'passed')) {
                $table->boolean('passed')->default(false)->after('percentage');
            }
            if (! Schema::hasColumn('results', 'status')) {
                $table->string('status')->default('submitted')->after('passed');
            }
            if (! Schema::hasColumn('results', 'submitted_at')) {
                $table->dateTime('submitted_at')->nullable()->after('status');
            }
        });

        $defaultAccessTypeId = DB::table('access_types')->where('code', 'FREE')->value('id');
        if (! $defaultAccessTypeId) {
            $defaultAccessTypeId = DB::table('access_types')->insertGetId([
                'name' => 'FREE',
                'code' => 'FREE',
                'description' => 'Default free access type.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('users')->whereNull('access_type_id')->update(['access_type_id' => $defaultAccessTypeId]);

        $defaultPermissions = [
            ['name' => 'exam.view', 'label' => 'View Exams', 'group_name' => 'Exam'],
            ['name' => 'exam.create', 'label' => 'Create Exams', 'group_name' => 'Exam'],
            ['name' => 'exam.edit', 'label' => 'Edit Exams', 'group_name' => 'Exam'],
            ['name' => 'exam.delete', 'label' => 'Delete Exams', 'group_name' => 'Exam'],
            ['name' => 'exam.publish', 'label' => 'Publish Exams', 'group_name' => 'Exam'],
            ['name' => 'exam.schedule', 'label' => 'Schedule Exams', 'group_name' => 'Exam'],
            ['name' => 'question.view', 'label' => 'View Questions', 'group_name' => 'Question'],
            ['name' => 'question.create', 'label' => 'Create Questions', 'group_name' => 'Question'],
            ['name' => 'question.edit', 'label' => 'Edit Questions', 'group_name' => 'Question'],
            ['name' => 'question.delete', 'label' => 'Delete Questions', 'group_name' => 'Question'],
            ['name' => 'question.assign', 'label' => 'Assign Questions', 'group_name' => 'Question'],
            ['name' => 'question.set_marks', 'label' => 'Set Question Marks', 'group_name' => 'Question'],
            ['name' => 'result.view', 'label' => 'View Results', 'group_name' => 'Result'],
            ['name' => 'result.export', 'label' => 'Export Results', 'group_name' => 'Result'],
            ['name' => 'result.manage', 'label' => 'Manage Results', 'group_name' => 'Result'],
            ['name' => 'user.view', 'label' => 'View Users', 'group_name' => 'User'],
            ['name' => 'user.create', 'label' => 'Create Users', 'group_name' => 'User'],
            ['name' => 'user.edit', 'label' => 'Edit Users', 'group_name' => 'User'],
            ['name' => 'user.delete', 'label' => 'Delete Users', 'group_name' => 'User'],
            ['name' => 'user.assign_role', 'label' => 'Assign User Roles', 'group_name' => 'User'],
            ['name' => 'user.assign_access_type', 'label' => 'Assign User Access Type', 'group_name' => 'User'],
            ['name' => 'role.view', 'label' => 'View Roles', 'group_name' => 'Role'],
            ['name' => 'role.create', 'label' => 'Create Roles', 'group_name' => 'Role'],
            ['name' => 'role.edit', 'label' => 'Edit Roles', 'group_name' => 'Role'],
            ['name' => 'role.delete', 'label' => 'Delete Roles', 'group_name' => 'Role'],
            ['name' => 'role.assign_permission', 'label' => 'Assign Role Permissions', 'group_name' => 'Role'],
            ['name' => 'settings.view', 'label' => 'View Settings', 'group_name' => 'Settings'],
            ['name' => 'settings.manage', 'label' => 'Manage Settings', 'group_name' => 'Settings'],
        ];

        foreach ($defaultPermissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                [
                    'label' => $permission['label'],
                    'group_name' => $permission['group_name'],
                    'description' => $permission['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $defaultAccessTypes = [
            ['name' => 'FREE', 'code' => 'FREE', 'description' => 'Free access type', 'is_active' => true],
            ['name' => 'ST-1', 'code' => 'ST-1', 'description' => 'Starter tier 1', 'is_active' => true],
            ['name' => 'SR-2', 'code' => 'SR-2', 'description' => 'Senior tier 2', 'is_active' => true],
            ['name' => 'ST-3', 'code' => 'ST-3', 'description' => 'Starter tier 3', 'is_active' => true],
        ];

        foreach ($defaultAccessTypes as $accessType) {
            DB::table('access_types')->updateOrInsert(
                ['code' => $accessType['code']],
                [
                    'name' => $accessType['name'],
                    'description' => $accessType['description'],
                    'is_active' => $accessType['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $roles = [
            ['name' => 'Super Admin', 'label' => 'Super Admin', 'description' => 'Full system access', 'is_active' => true],
            ['name' => 'Admin', 'label' => 'Admin', 'description' => 'Administrative access with assigned permissions', 'is_active' => true],
            ['name' => 'Exam Manager', 'label' => 'Exam Manager', 'description' => 'Manage exams and results', 'is_active' => true],
            ['name' => 'Teacher', 'label' => 'Teacher', 'description' => 'Teacher-only features', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                [
                    'label' => $role['label'],
                    'description' => $role['description'],
                    'is_active' => $role['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $superAdminRoleId = DB::table('roles')->where('name', 'Super Admin')->value('id');
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');
        $managerRoleId = DB::table('roles')->where('name', 'Exam Manager')->value('id');
        $teacherRoleId = DB::table('roles')->where('name', 'Teacher')->value('id');

        $allPermissionIds = DB::table('permissions')->pluck('id')->all();
        if ($superAdminRoleId) {
            foreach ($allPermissionIds as $permissionId) {
                DB::table('role_permissions')->updateOrInsert([
                    'role_id' => $superAdminRoleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        if ($adminRoleId) {
            $adminPermissions = DB::table('permissions')->whereIn('name', [
                'exam.view', 'exam.create', 'exam.edit', 'exam.delete', 'exam.publish', 'exam.schedule',
                'question.view', 'question.create', 'question.edit', 'question.delete', 'question.assign', 'question.set_marks',
                'result.view', 'result.export', 'result.manage',
                'user.view', 'user.create', 'user.edit', 'user.delete', 'user.assign_role', 'user.assign_access_type',
                'role.view', 'role.create', 'role.edit', 'role.delete', 'role.assign_permission',
                'settings.view', 'settings.manage',
            ])->pluck('id')->all();

            foreach ($adminPermissions as $permissionId) {
                DB::table('role_permissions')->updateOrInsert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        if ($managerRoleId) {
            $managerPermissions = DB::table('permissions')->whereIn('name', [
                'exam.view', 'exam.create', 'exam.edit', 'exam.schedule', 'exam.publish',
                'question.view', 'question.create', 'question.edit', 'question.assign', 'question.set_marks',
                'result.view', 'result.manage', 'result.export',
            ])->pluck('id')->all();

            foreach ($managerPermissions as $permissionId) {
                DB::table('role_permissions')->updateOrInsert([
                    'role_id' => $managerRoleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        if ($teacherRoleId) {
            $teacherPermissions = DB::table('permissions')->whereIn('name', [
                'exam.view', 'question.view', 'question.create', 'question.edit', 'result.view',
            ])->pluck('id')->all();

            foreach ($teacherPermissions as $permissionId) {
                DB::table('role_permissions')->updateOrInsert([
                    'role_id' => $teacherRoleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_access_types');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('access_types');
    }
};

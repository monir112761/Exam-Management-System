<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('questions')) {
            return;
        }

        $columns = [
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'correct_answer',
        ];

        foreach ($columns as $column) {
            if (! Schema::hasColumn('questions', $column)) {
                continue;
            }

            DB::statement('ALTER TABLE `questions` MODIFY `'.$column.'` VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('questions')) {
            return;
        }

        foreach (['option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'] as $column) {
            if (Schema::hasColumn('questions', $column)) {
                DB::statement('ALTER TABLE `questions` MODIFY `'.$column.'` VARCHAR(255) NOT NULL DEFAULT ""');
            }
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (! Schema::hasColumn('questions', 'subject_name')) {
                $table->string('subject_name')->nullable()->after('exam_id');
            }

            if (! Schema::hasColumn('questions', 'question_type')) {
                $table->string('question_type')->default('mcq')->after('subject_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'question_type')) {
                $table->dropColumn('question_type');
            }

            if (Schema::hasColumn('questions', 'subject_name')) {
                $table->dropColumn('subject_name');
            }
        });
    }
};

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
        Schema::table('test_questions', function (Blueprint $table) {
            $table->enum('question_type', ['mcq', 'likert', 'scenario'])->default('mcq')->after('question');
            $table->json('options')->nullable()->after('option_d'); // For flexible options storage
            $table->string('correct_answer')->nullable()->change(); // Make nullable for non-MCQ questions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->dropColumn(['question_type', 'options']);
            $table->string('correct_answer')->nullable(false)->change();
        });
    }
};

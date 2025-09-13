<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing questions to be MCQ type
        DB::table('test_questions')->update(['question_type' => 'mcq']);
        
        // Modify the table to only allow MCQ type
        Schema::table('test_questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
            $table->dropColumn('options');
        });
        
        // Make sure all MCQ fields are required
        Schema::table('test_questions', function (Blueprint $table) {
            $table->string('option_a')->nullable(false)->change();
            $table->string('option_b')->nullable(false)->change();
            $table->string('option_c')->nullable(false)->change();
            $table->string('option_d')->nullable(false)->change();
            $table->enum('correct_answer', ['a', 'b', 'c', 'd'])->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->enum('question_type', ['mcq', 'likert', 'scenario'])->default('mcq')->after('question');
            $table->json('options')->nullable()->after('option_d');
            $table->string('correct_answer')->nullable()->change();
        });
    }
};


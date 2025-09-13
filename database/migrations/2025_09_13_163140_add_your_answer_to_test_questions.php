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
            if (!Schema::hasColumn('test_questions', 'your_answer')) {
                $table->enum('your_answer', ['a', 'b', 'c', 'd'])
                      ->nullable()
                      ->after('option_d');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            if (Schema::hasColumn('test_questions', 'your_answer')) {
                $table->dropColumn('your_answer');
            }
        });
    }
};
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
            if (Schema::hasColumn('test_questions', 'score')) {
                $table->dropColumn('score');
            }
            if (Schema::hasColumn('test_questions', 'points')) {
                $table->dropColumn('points');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->integer('score')->default(1)->nullable();
            $table->integer('points')->default(1)->nullable();
        });
    }
};


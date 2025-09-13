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
        // تحقق أولًا من أن الجدول موجود قبل أي تعديل
        if (Schema::hasTable('test_questions')) {
            Schema::table('test_questions', function (Blueprint $table) {
                // Make option columns nullable with default empty string
                $table->string('option_a')->nullable()->default('')->change();
                $table->string('option_b')->nullable()->default('')->change();
                $table->string('option_c')->nullable()->default('')->change();
                $table->string('option_d')->nullable()->default('')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // تحقق من وجود الجدول قبل التراجع
        if (Schema::hasTable('test_questions')) {
            Schema::table('test_questions', function (Blueprint $table) {
                // Revert back to NOT NULL
                $table->string('option_a')->nullable(false)->default(null)->change();
                $table->string('option_b')->nullable(false)->default(null)->change();
                $table->string('option_c')->nullable(false)->default(null)->change();
                $table->string('option_d')->nullable(false)->default(null)->change();
            });
        }
    }
};

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
        Schema::table("test_questions", function (Blueprint $table) {
            if (Schema::hasColumn("test_questions", "question_type")) {
                // تحديث جميع الأسئلة الموجودة لتكون من نوع msq
                DB::table("test_questions")->update(["question_type" => "msq"]);
                
                // تعديل العمود ليكون له قيمة افتراضية
                $table->string("question_type")->default("msq")->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->string('question_type')->nullable()->change();
        });
    }
};


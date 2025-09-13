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
        Schema::create('user_test_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->json('answers_data'); // تخزين جميع إجابات المستخدم مع تفاصيل الأسئلة
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->timestamp('completed_at');
            $table->timestamps();
            
            // فهرس مركب لضمان عدم تكرار التقرير لنفس المستخدم والاختبار
            $table->unique(['user_id', 'test_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_test_reports');
    }
};


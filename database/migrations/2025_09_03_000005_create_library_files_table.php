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
        Schema::create('library_files', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الملف
            $table->text('description')->nullable(); // وصف الملف
            $table->string('file_name'); // اسم الملف المحفوظ
            $table->string('original_name'); // الاسم الأصلي للملف
            $table->string('file_type'); // نوع الملف (pdf, doc, ppt, etc.)
            $table->bigInteger('file_size'); // حجم الملف بالبايت
            $table->string('category')->nullable(); 
            $table->boolean('is_active')->default(true); 
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade'); // من رفع الملف
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_files');
    }
};


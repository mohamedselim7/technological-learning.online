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
       Schema::create('learning_day_videos', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('learning_day_id');
    $table->string('video_path');
    $table->string('original_name')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamp('activation_date')->nullable();
    $table->timestamps();

    $table->foreign('learning_day_id')
          ->references('id')
          ->on('learning_days')
          ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_day_videos');
    }
};

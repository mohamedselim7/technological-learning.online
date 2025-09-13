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
            $table->foreignId('learning_day_id')->constrained()->onDelete('cascade');
            $table->string('video_path'); // path to uploaded video
            $table->boolean('is_active')->default(false);
            $table->timestamps();
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

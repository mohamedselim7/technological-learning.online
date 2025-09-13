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
        Schema::create('day_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_day_id')->constrained()->onDelete('cascade');
            $table->enum('experience_level', ['أقل من 15 سنة', 'أكثر من 15 سنة']);
            $table->text('task');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_tasks');
    }
};

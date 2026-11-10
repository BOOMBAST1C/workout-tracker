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
    Schema::create('user_workout', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('workout_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        
        // Запрещаем дубликаты
        $table->unique(['user_id', 'workout_id']);
    });
}

public function down(): void
{
    Schema::dropIfExists('user_workout');
}
};

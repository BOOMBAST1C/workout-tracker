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
    Schema::create('workouts', function (Blueprint $table) {
        $table->id();
        
        // Связь с пользователями. При удалении пользователя удаляются и его тренировки
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->date('workout_date'); // Дата тренировки
        $table->string('title')->nullable(); // Название (например, "День ног")
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};

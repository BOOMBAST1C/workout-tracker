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
    Schema::create('sets', function (Blueprint $table) {
        $table->id();
        
        // Связь с тренировками. При удалении тренировки удаляются и её подходы
        $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
        
        // Связь с упражнениями. Запрещаем удалять упражнение, если оно есть в подходах (история)
        $table->foreignId('exercise_id')->constrained('exercises')->onDelete('restrict');
        
        $table->integer('set_number'); // Номер подхода (1, 2, 3...)
        $table->decimal('weight_kg', 8, 2)->default(0); // Вес (например, 100.50 кг)
        $table->integer('reps')->default(0); // Количество повторений
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};

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
    Schema::create('exercises', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Название упражнения (Жим лежа, Приседания)
        
        // Связь с группами мышц. Запрещаем удалять группу, если в ней есть упражнения
        $table->foreignId('muscle_group_id')->constrained('muscle_groups')->onDelete('restrict');
        
        $table->text('description')->nullable(); // Описание техники (необязательно)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};

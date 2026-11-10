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
    Schema::table('workouts', function (Blueprint $table) {
        // Тренер, который провёл тренировку (может быть null, если пользователь тренировался сам)
        $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('workouts', function (Blueprint $table) {
        $table->dropForeign(['trainer_id']);
        $table->dropColumn('trainer_id');
    });
}
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'set_number',
        'weight_kg',
        'reps',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
    ];

    /**
     * Связь: Подход принадлежит ОДНОЙ тренировке
     * (Many-to-One)
     */
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    /**
     * Связь: Подход принадлежит ОДНОМУ упражнению
     * (Many-to-One)
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
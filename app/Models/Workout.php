<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = [
        'user_id',
        'workout_date',
        'title',
    ];

    protected $casts = [
        'workout_date' => 'date',
    ];

    /**
     * Связь: Тренировка принадлежит ОДНОМУ пользователю
     * (Many-to-One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь: У тренировки МНОГО подходов
     * (One-to-Many)
     */
    public function sets()
    {
        return $this->hasMany(Set::class);
    }

    /**
     * Связь: Упражнения, которые выполнялись в этой тренировке
     * (через таблицу sets)
     */
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'sets')
            ->withPivot('set_number', 'weight_kg', 'reps')
            ->withTimestamps();
    }
}
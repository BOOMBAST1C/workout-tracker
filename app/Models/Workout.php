<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = [
        'user_id',
        'trainer_id',
        'workout_date',
        'title',
    ];

    protected $casts = [
        'workout_date' => 'date',
    ];

    /**
     * Связь: Тренировка принадлежит ОДНОМУ основному пользователю (автору)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Связь: Тренировка проведена ТРЕНЕРОМ
     */
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /**
     * Связь: У тренировки МНОГО подходов
     */
    public function sets()
    {
        return $this->hasMany(Set::class);
    }

    /**
     * Связь: Пользователи, участвовавшие в этой тренировке (для групповых)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workout')->withTimestamps();
    }
}
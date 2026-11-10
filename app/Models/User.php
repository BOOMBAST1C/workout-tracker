<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'login',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Связь: У пользователя ОДНА роль
     * (Many-to-One)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Связь: У пользователя МНОГО тренировок
     * (One-to-Many)
     */

    /**
     * Проверка: является ли пользователь админом
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Проверка: является ли пользователь тренером
     */
    public function isTrainer(): bool
    {
        return $this->role && $this->role->name === 'trainer';
    }
    // Пользователь принадлежит тренеру
// Тренировки, где пользователь является участником (лично или в группе)
    public function workouts()
    {
    return $this->belongsToMany(Workout::class, 'user_workout')->withTimestamps();
    }

// Тренировки, которые пользователь провёл как тренер
    public function trainedWorkouts()
{
    return $this->hasMany(Workout::class, 'trainer_id');
}

// Подопечные (пользователи, у которых этот пользователь является тренером хотя бы в одной тренировке)
    public function clients()
{
    return $this->belongsToMany(User::class, 'user_workout', 'trainer_id', 'user_id')->distinct();
    // Примечание: для простоты мы будем получать клиентов через запрос в контроллере, 
    // но эта связь полезна для понимания.
}
}
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
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

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
}
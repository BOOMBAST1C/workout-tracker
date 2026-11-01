<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    /**
     * Связь: У одной роли может быть МНОГО пользователей
     * (One-to-Many)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
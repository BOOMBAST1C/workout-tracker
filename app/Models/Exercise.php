<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'muscle_group_id',
        'description',
    ];

    /**
     * Связь: Упражнение принадлежит ОДНОЙ группе мышц
     * (Many-to-One)
     */
    public function muscleGroup()
    {
        return $this->belongsTo(MuscleGroup::class);
    }

    /**
     * Связь: Упражнение может встречаться во МНОГИХ подходах
     * (One-to-Many)
     */
    public function sets()
    {
        return $this->hasMany(Set::class);
    }
}
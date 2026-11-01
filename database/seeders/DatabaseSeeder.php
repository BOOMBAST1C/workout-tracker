<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\MuscleGroup;
use App\Models\Exercise;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаем роли
        $roles = ['admin', 'trainer', 'user'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Создаем группы мышц и сразу добавляем в них упражнения
        $muscleGroupsData = [
            'Грудь' => [
                'Жим штанги лежа',
                'Жим гантелей на наклонной скамье',
                'Разводка гантелей',
                'Отжимания на брусьях (акцент на грудь)'
            ],
            'Спина' => [
                'Подтягивания',
                'Тяга верхнего блока к груди',
                'Тяга штанги в наклоне',
                'Гиперэкстензия'
            ],
            'Ноги' => [
                'Приседания со штангой',
                'Жим ногами в тренажере',
                'Выпады с гантелями',
                'Сгибание ног в тренажере'
            ],
            'Руки' => [
                'Подъем штанги на бицепс',
                'Французский жим',
                'Молотки с гантелями',
                'Разгибание рук на верхнем блоке'
            ],
            'Плечи' => [
                'Жим гантелей сидя',
                'Махи гантелями в стороны',
                'Тяга штанги к подбородку'
            ],
            'Пресс' => [
                'Скручивания на полу',
                'Подъем ног в висе',
                'Планка'
            ],
            'Кардио' => [
                'Бег на беговой дорожке',
                'Велотренажер',
                'Гребной тренажер'
            ]
        ];

        foreach ($muscleGroupsData as $groupName => $exercises) {
            // Создаем или находим группу мышц
            $muscleGroup = MuscleGroup::firstOrCreate(['name' => $groupName]);
            
            // Создаем упражнения для этой группы
            foreach ($exercises as $exerciseName) {
                Exercise::firstOrCreate([
                    'name' => $exerciseName,
                    'muscle_group_id' => $muscleGroup->id,
                    'description' => 'Базовое упражнение для развития группы мышц: ' . $groupName
                ]);
            }
        }
    }
}
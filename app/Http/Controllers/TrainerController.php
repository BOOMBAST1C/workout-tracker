<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    // Список всех подопечных (пользователей с ролью user)
    public function clients()
    {
        $clients = User::where('role_id', 3)->with('role')->get(); // 3 = ID роли 'user'
        return view('trainer.clients', compact('clients'));
    }

    // График прогресса конкретного подопечного
    public function progress($userId)
    {
        $client = User::findOrFail($userId);
        $exercises = Exercise::all();

        // Получаем данные для графика: максимальный вес по каждому упражнению в хронологическом порядке
        $progressData = DB::table('sets')
            ->join('workouts', 'sets.workout_id', '=', 'workouts.id')
            ->join('exercises', 'sets.exercise_id', '=', 'exercises.id')
            ->where('workouts.user_id', $userId) // Или whereHas для user_workout, но для простоты возьмем user_id
            ->select('exercises.name as exercise_name', 'workouts.workout_date', DB::raw('MAX(sets.weight_kg) as max_weight'))
            ->groupBy('exercises.name', 'workouts.workout_date')
            ->orderBy('workouts.workout_date', 'asc')
            ->get();

        // Форматируем данные для Chart.js
        $chartLabels = $progressData->pluck('workout_date')->unique()->values();
        
        $datasets = [];
        $colors = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6'];
        $colorIndex = 0;

        foreach ($progressData->groupBy('exercise_name') as $exName => $data) {
            // Заполняем пропуски null, чтобы график был ровным
            $weights = $chartLabels->map(function ($date) use ($data) {
                $record = $data->firstWhere('workout_date', $date);
                return $record ? (float)$record->max_weight : null;
            });

            $datasets[] = [
                'label' => $exName,
                'data' => $weights->toArray(),
                'borderColor' => $colors[$colorIndex % count($colors)],
                'backgroundColor' => $colors[$colorIndex % count($colors)] . '33', // с прозрачностью
                'fill' => false,
                'tension' => 0.3
            ];
            $colorIndex++;
        }

        return view('trainer.progress', compact('client', 'chartLabels', 'datasets'));
    }
}
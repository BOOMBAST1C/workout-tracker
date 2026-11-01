<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Если пользователь не авторизован, показываем просто приветствие
        if (!$user) {
            return view('pages.home');
        }
        
        // Статистика: количество тренировок за текущий месяц
        $workoutsThisMonth = Workout::where('user_id', $user->id)
            ->whereMonth('workout_date', now()->month)
            ->whereYear('workout_date', now()->year)
            ->count();
        
        // Статистика: общее количество тренировок
        $totalWorkouts = Workout::where('user_id', $user->id)->count();
        
        // Статистика: самое часто выполняемое упражнение
        $topExercise = DB::table('sets')
            ->join('workouts', 'sets.workout_id', '=', 'workouts.id')
            ->join('exercises', 'sets.exercise_id', '=', 'exercises.id')
            ->where('workouts.user_id', $user->id)
            ->select('exercises.name', DB::raw('count(*) as total'))
            ->groupBy('exercises.name')
            ->orderByDesc('total')
            ->first();
        
        // Лента последних тренировок (с пагинацией)
        $workouts = Workout::with(['user', 'sets.exercise'])
            ->where('user_id', $user->id)
            ->orderBy('workout_date', 'desc')
            ->paginate(5);
        
        return view('pages.home', compact(
            'workoutsThisMonth',
            'totalWorkouts',
            'topExercise',
            'workouts'
        ));
    }
}
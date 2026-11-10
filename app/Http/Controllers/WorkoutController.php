<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Exercise;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    /**
     * Показать список тренировок пользователя
     */
    public function index()
{
    $user = auth()->user();

    // Базовый запрос: получаем тренировки, в которых участвует текущий пользователь
    $query = Workout::with(['users', 'sets.exercise', 'trainer'])
        ->whereHas('users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });

    // Если это тренер или админ, он также видит тренировки, которые он провёл (даже если забыл добавить себя в участники)
    if ($user->isTrainer() || $user->isAdmin()) {
        $query->orWhere('trainer_id', $user->id);
    }

    $workouts = $query->orderBy('workout_date', 'desc')->paginate(10);
    
    return view('workouts.index', compact('workouts'));
}

    /**
     * Показать форму создания тренировки
     */
    public function create()
    {
        $exercises = Exercise::with('muscleGroup')->orderBy('name')->get();
        return view('workouts.create', compact('exercises'));
    }

    /**
     * Сохранить новую тренировку с подходами
     */
    /**
     * Сохранить новую тренировку с подходами
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workout_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'user_ids' => 'required|array|min:1', // Массив ID пользователей (для личных и групповых)
            'user_ids.*' => 'exists:users,id',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|array|min:1',
            'exercises.*.sets.*.set_number' => 'required|integer|min:1',
            'exercises.*.sets.*.weight_kg' => 'required|numeric|min:0',
            'exercises.*.sets.*.reps' => 'required|integer|min:1',
        ]);

        // 1. Создаем тренировку
        $workout = Workout::create([
            'user_id' => auth()->id(),       // ⭐ ДОБАВЬТЕ ЭТУ СТРОКУ (чтобы удовлетворить требование БД NOT NULL)
            'trainer_id' => auth()->id(),    // Тот, кто создает, является тренером
            'workout_date' => $validated['workout_date'],
            'title' => $validated['title'],
        ]);

        // 2. Привязываем пользователей через таблицу user_workout (для групповых)
        $workout->users()->attach($validated['user_ids']);

        // 3. Создаем подходы
        foreach ($validated['exercises'] as $exerciseData) {
            foreach ($exerciseData['sets'] as $setData) {
                Set::create([
                    'workout_id' => $workout->id,
                    'exercise_id' => $exerciseData['exercise_id'],
                    'set_number' => $setData['set_number'],
                    'weight_kg' => $setData['weight_kg'],
                    'reps' => $setData['reps'],
                ]);
            }
        }

        return redirect()->route('workouts.index')->with('success', 'Тренировка успешно сохранена!');
    }
    /**
     * Удалить тренировку
     */
    public function destroy($id)
    {
        // Находим тренировку, принадлежащую именно текущему пользователю (безопасность)
        $workout = Workout::where('user_id', Auth::id())->findOrFail($id);
        $workout->delete(); // Каскадное удаление подходов сработает автоматически благодаря БД
        
        return redirect()->route('workouts.index')->with('success', 'Тренировка удалена!');
    }
    /**
 * Показать одну тренировку подробно
 */
public function show($id)
{
    $workout = Workout::with(['user', 'sets.exercise.muscleGroup'])->findOrFail($id);
    $user = auth()->user();

    // Доступ разрешен, если: это его тренировка ИЛИ он админ/тренер
    if ($workout->user_id !== $user->id && !$user->isAdmin() && !$user->isTrainer()) {
        abort(403, 'У вас нет прав для просмотра этой тренировки.');
    }

    return view('workouts.show', compact('workout'));
}
}
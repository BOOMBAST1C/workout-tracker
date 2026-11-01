<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\MuscleGroup;
use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Главная страница админки
    public function index()
    {
        $users = User::with('role')->paginate(10);
        $roles = Role::all();
        $muscleGroups = MuscleGroup::withCount('exercises')->get();
        $exercisesCount = Exercise::count();
        $workoutsCount = Workout::count();

        return view('admin.index', compact('users', 'roles', 'muscleGroups', 'exercisesCount', 'workoutsCount'));
    }

    // === УПРАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯМИ ===
    public function assignRole(Request $request, $userId)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($userId);
        $user->update(['role_id' => $validated['role_id']]);

        return redirect()->route('admin')->with('success', 'Роль пользователя изменена!');
    }

    // === УПРАВЛЕНИЕ ГРУППАМИ МЫШЦ ===
    public function storeMuscleGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:muscle_groups,name',
        ]);

        MuscleGroup::create(['name' => $validated['name']]);
        return redirect()->route('admin')->with('success', 'Группа мышц добавлена!');
    }

    public function updateMuscleGroup(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:muscle_groups,name,' . $id,
        ]);

        $group = MuscleGroup::findOrFail($id);
        $group->update(['name' => $validated['name']]);

        return redirect()->route('admin')->with('success', 'Группа мышц обновлена!');
    }

    public function destroyMuscleGroup($id)
    {
        try {
            $group = MuscleGroup::findOrFail($id);
            $group->delete();
            return redirect()->route('admin')->with('success', 'Группа мышц удалена!');
        } catch (\Exception $e) {
            return redirect()->route('admin')->with('error', 'Нельзя удалить группу, к которой привязаны упражнения!');
        }
    }

    // === УПРАВЛЕНИЕ УПРАЖНЕНИЯМИ ===
    public function storeExercise(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
            'description' => 'nullable|string',
        ]);

        Exercise::create($validated);
        return redirect()->route('admin')->with('success', 'Упражнение добавлено!');
    }

    public function updateExercise(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'muscle_group_id' => 'required|exists:muscle_groups,id',
            'description' => 'nullable|string',
        ]);

        $exercise = Exercise::findOrFail($id);
        $exercise->update($validated);

        return redirect()->route('admin')->with('success', 'Упражнение обновлено!');
    }

    public function destroyExercise($id)
    {
        try {
            $exercise = Exercise::findOrFail($id);
            $exercise->delete();
            return redirect()->route('admin')->with('success', 'Упражнение удалено!');
        } catch (\Exception $e) {
            return redirect()->route('admin')->with('error', 'Нельзя удалить упражнение, которое используется в тренировках!');
        }
    }
}
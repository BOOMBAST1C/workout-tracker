<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Получаем все группы мышц для фильтра
        $muscleGroups = MuscleGroup::all();
        
        // Базовый запрос
        $query = Exercise::with('muscleGroup');
        
        // Если выбран фильтр по группе мышц
        if ($request->has('group') && $request->group != '') {
            $query->where('muscle_group_id', $request->group);
        }
        
        // Получаем упражнения с пагинацией
        $exercises = $query->orderBy('name')->paginate(15);
        
        return view('pages.catalog', compact('exercises', 'muscleGroups'));
    }
}
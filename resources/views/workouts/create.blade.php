@extends('layouts.app')

@section('title', 'Новая тренировка')

@section('content')
    <h2>Создание новой тренировки</h2>

    @if ($errors->any())
        <div style="background: #fee; color: #c00; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <strong>Ошибки:</strong>
            <ul style="margin: 5px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('workouts.store') }}" method="POST">
        @csrf
        
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="font-weight: bold;">Дата тренировки:</label><br>
                <input type="date" name="workout_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="flex: 2;">
                <label style="font-weight: bold;">Название (опционально):</label><br>
                <input type="text" name="title" placeholder="Например: День ног" style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
        </div>

        <hr style="margin: 20px 0;">
        
        <div id="exercises-container">
            <!-- Сюда JavaScript будет добавлять блоки упражнений -->
        </div>

        <button type="button" onclick="addExercise()" class="btn btn-primary" style="margin-top: 10px;">+ Добавить упражнение</button>

        <hr style="margin: 30px 0;">
        <button type="submit" class="btn btn-success" style="font-size: 16px; padding: 12px 24px;">Сохранить тренировку</button>
    </form>

    {{-- Шаблон для JavaScript --}}
    <template id="exercise-template">
        <div class="exercise-block" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px; background: #fafafa;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <select name="exercises[__INDEX__][exercise_id]" required style="flex: 1; padding: 8px; margin-right: 10px;">
                    <option value="">-- Выберите упражнение --</option>
                    @foreach ($exercises as $exercise)
                        <option value="{{ $exercise->id }}">{{ $exercise->muscleGroup->name }}: {{ $exercise->name }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="this.closest('.exercise-block').remove()" class="btn btn-danger">Удалить упражнение</button>
            </div>
            
            <div class="sets-container">
                <!-- Сюда добавляются подходы -->
            </div>
            
            <button type="button" onclick="addSet(this)" class="btn btn-primary" style="font-size: 12px; padding: 5px 10px;">+ Добавить подход</button>
        </div>
    </template>

    <template id="set-template">
        <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
            <input type="number" name="exercises[__EX_INDEX__][sets][__SET_INDEX__][set_number]" placeholder="№" min="1" required style="width: 60px; padding: 8px;">
            <input type="number" step="0.5" name="exercises[__EX_INDEX__][sets][__SET_INDEX__][weight_kg]" placeholder="Вес (кг)" min="0" required style="width: 100px; padding: 8px;">
            <input type="number" name="exercises[__EX_INDEX__][sets][__SET_INDEX__][reps]" placeholder="Повторы" min="1" required style="width: 100px; padding: 8px;">
            <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger" style="font-size: 12px;">✕</button>
        </div>
    </template>

    <script>
        let exerciseIndex = 0;

        function addExercise() {
            const container = document.getElementById('exercises-container');
            const template = document.getElementById('exercise-template').innerHTML;
            const newBlock = template.replace(/__INDEX__/g, exerciseIndex);
            container.insertAdjacentHTML('beforeend', newBlock);
            
            // Сразу добавляем один подход для нового упражнения
            const setsContainer = container.lastElementChild.querySelector('.sets-container');
            addSetToContainer(setsContainer, exerciseIndex, 0);
            
            exerciseIndex++;
        }

        function addSet(button) {
            const exerciseBlock = button.closest('.exercise-block');
            const setsContainer = exerciseBlock.querySelector('.sets-container');
            const exIndex = Array.from(document.querySelectorAll('.exercise-block')).indexOf(exerciseBlock);
            const setIndex = setsContainer.children.length;
            addSetToContainer(setsContainer, exIndex, setIndex);
        }

        function addSetToContainer(container, exIndex, setIndex) {
            const template = document.getElementById('set-template').innerHTML;
            const newSet = template
                .replace(/__EX_INDEX__/g, exIndex)
                .replace(/__SET_INDEX__/g, setIndex);
            container.insertAdjacentHTML('beforeend', newSet);
        }

        // Добавляем первое упражнение при загрузке
        window.onload = function() {
            addExercise();
        };
    </script>
@endsection
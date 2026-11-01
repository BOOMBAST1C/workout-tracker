@extends('layouts.app')

@section('title', 'Мои тренировки')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Мои тренировки</h2>
        <a href="{{ route('workouts.create') }}" class="btn btn-success">+ Новая тренировка</a>
    </div>

    @forelse ($workouts as $workout)
        <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="margin: 0;">{{ $workout->title ?? 'Без названия' }}</h3>
                <div>
                    <span style="color: #666; font-size: 14px; margin-right: 15px;">
                        {{ \Carbon\Carbon::parse($workout->workout_date)->format('d.m.Y') }}
                    </span>
                    <form action="{{ route('workouts.destroy', $workout->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 5px 10px;" onclick="return confirm('Удалить эту тренировку?')">Удалить</button>
                    </form>
                </div>
            </div>
            
            <div style="background: #f8f9fa; padding: 10px; border-radius: 4px;">
                <strong>Выполненные упражнения:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    @php
                        // Группируем подходы по названиям упражнений для красивого вывода
                        $groupedSets = $workout->sets->groupBy('exercise.name');
                    @endphp
                    @foreach ($groupedSets as $exName => $sets)
                        <li style="margin-bottom: 8px;">
                            <strong>{{ $exName }}:</strong> 
                            @foreach ($sets as $set)
                                Подход {{ $set->set_number }}: {{ $set->weight_kg }} кг × {{ $set->reps }} повт.
                                @if (!$loop->last), @endif
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>У вас пока нет записанных тренировок.</p>
            <a href="{{ route('workouts.create') }}" class="btn btn-primary">Создать первую тренировку</a>
        </div>
    @endforelse

    @if ($workouts->hasPages())
        <div style="margin-top: 30px;">
            {{ $workouts->links() }}
        </div>
    @endif
@endsection
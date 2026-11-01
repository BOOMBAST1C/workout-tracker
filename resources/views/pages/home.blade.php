@extends('layouts.app')

@section('title', 'Главная - Workout Tracker')

@section('content')
    @auth
        <h2>Добро пожаловать, {{ auth()->user()->login }}!</h2>
        
        {{-- Блок статистики --}}
        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div style="flex: 1; background: #3498db; color: white; padding: 20px; border-radius: 8px; text-align: center;">
                <div style="font-size: 32px; font-weight: bold;">{{ $workoutsThisMonth }}</div>
                <div>Тренировок в этом месяце</div>
            </div>
            <div style="flex: 1; background: #2ecc71; color: white; padding: 20px; border-radius: 8px; text-align: center;">
                <div style="font-size: 32px; font-weight: bold;">{{ $totalWorkouts }}</div>
                <div>Всего тренировок</div>
            </div>
            <div style="flex: 1; background: #e74c3c; color: white; padding: 20px; border-radius: 8px; text-align: center;">
                <div style="font-size: 24px; font-weight: bold;">{{ $topExercise ? $topExercise->name : 'Нет данных' }}</div>
                <div>Частое упражнение</div>
            </div>
        </div>

        {{-- Лента тренировок --}}
        <h3>Мои последние тренировки</h3>
        
        @forelse ($workouts as $workout)
            <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="margin: 0;">
                        {{ $workout->title ?? 'Тренировка' }}
                    </h3>
                    <span style="color: #666; font-size: 14px;">
                        {{ $workout->workout_date->format('d.m.Y') }}
                    </span>
                </div>
                <p style="margin: 0 0 10px 0; color: #666;">
                    <strong>Упражнений:</strong> {{ $workout->sets->count() }}
                </p>
                <div style="margin-top: 10px;">
                    <a href="{{ route('workouts.show', $workout->id) }}" class="btn btn-primary" style="font-size: 14px;">Подробнее</a>
                </div>
            </div>
        @empty
            <p style="text-align: center; color: #666; padding: 40px;">
                У вас пока нет тренировок. 
                <a href="{{ route('workouts.create') }}" style="color: #3498db;">Создать первую тренировку</a>
            </p>
        @endforelse

        @if ($workouts->hasPages())
            <div style="margin-top: 30px;">
                {{ $workouts->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <h1 style="font-size: 48px; margin-bottom: 20px;">🏋️ Workout Tracker</h1>
            <p style="font-size: 18px; color: #666; margin-bottom: 30px;">
                Ведите дневник тренировок, анализируйте прогресс и достигайте целей!
            </p>
            <a href="{{ route('register') }}" class="btn btn-success" style="font-size: 18px; padding: 15px 30px;">
                Начать бесплатно
            </a>
        </div>
    @endauth
@endsection
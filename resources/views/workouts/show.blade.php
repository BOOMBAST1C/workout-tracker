@extends('layouts.app')

@section('title', $workout->title ?? 'Тренировка')

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ url()->previous() }}" class="btn" style="background: #95a5a6;">← Назад</a>
    </div>

    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">{{ $workout->title ?? 'Тренировка без названия' }}</h2>
            <span style="color: #666; font-size: 16px;">
                {{ \Carbon\Carbon::parse($workout->workout_date)->format('d.m.Y') }}
            </span>
        </div>

        <p style="color: #666; margin-bottom: 20px;">
            <strong>Автор:</strong> {{ $workout->user->login }} 
            <span style="background: #3498db; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;">
                {{ $workout->user->role->name }}
            </span>
        </p>

        <hr style="margin: 20px 0;">

        <h3>Упражнения и подходы</h3>

        @php
            $groupedSets = $workout->sets->groupBy('exercise.name');
        @endphp

        @foreach ($groupedSets as $exerciseName => $sets)
            @php $firstSet = $sets->first(); @endphp
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                <h4 style="margin: 0 0 5px 0;">
                    💪 {{ $exerciseName }}
                    <span style="font-size: 13px; color: #666; font-weight: normal;">
                        ({{ $firstSet->exercise->muscleGroup->name }})
                    </span>
                </h4>
                @if ($firstSet->exercise->description)
                    <p style="font-size: 13px; color: #777; margin: 5px 0 10px 0;">
                        {{ $firstSet->exercise->description }}
                    </p>
                @endif

                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 4px; overflow: hidden;">
                    <thead>
                        <tr style="background: #e9ecef;">
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">№ подхода</th>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Вес (кг)</th>
                            <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Повторения</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sets->sortBy('set_number') as $set)
                            <tr>
                                <td style="padding: 8px; border: 1px solid #ddd;">{{ $set->set_number }}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">{{ $set->weight_kg }}</td>
                                <td style="padding: 8px; border: 1px solid #ddd;">{{ $set->reps }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        @if ($workout->sets->isEmpty())
            <p style="color: #666; text-align: center; padding: 20px;">
                В этой тренировке нет подходов.
            </p>
        @endif
    </div>
@endsection
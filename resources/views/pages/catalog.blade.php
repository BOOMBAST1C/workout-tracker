@extends('layouts.app')

@section('title', 'Каталог упражнений')

@section('content')
    <h2>Каталог упражнений</h2>
    
    {{-- Фильтр по группам мышц --}}
    <div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <form action="{{ route('catalog') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <label for="group" style="font-weight: bold;">Фильтр по группе мышц:</label>
            <select name="group" id="group" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                <option value="">Все группы</option>
                @foreach ($muscleGroups as $group)
                    <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Применить</button>
            @if (request('group'))
                <a href="{{ route('catalog') }}" class="btn btn-danger">Сбросить</a>
            @endif
        </form>
    </div>

    {{-- Список упражнений --}}
    @forelse ($exercises as $exercise)
        <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
            <h3 style="margin: 0 0 10px 0;">{{ $exercise->name }}</h3>
            <p style="margin: 0 0 10px 0; color: #666;">
                <strong>Группа мышц:</strong> {{ $exercise->muscleGroup->name }}
            </p>
            @if ($exercise->description)
                <p style="margin: 0; color: #555;">{{ $exercise->description }}</p>
            @endif
        </div>
    @empty
        <p style="text-align: center; color: #666; padding: 40px;">
            В каталоге пока нет упражнений.
        </p>
    @endforelse

    {{-- Пагинация --}}
    @if ($exercises->hasPages())
        <div style="margin-top: 30px;">
            {{ $exercises->links() }}
        </div>
    @endif
@endsection
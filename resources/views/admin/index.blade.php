@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
    <h2>⚙️ Админ-панель</h2>

    {{-- Статистика --}}
    <div style="display: flex; gap: 15px; margin-bottom: 30px;">
        <div style="flex: 1; background: #3498db; color: white; padding: 15px; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: bold;">{{ $users->total() }}</div>
            <div>Пользователей</div>
        </div>
        <div style="flex: 1; background: #2ecc71; color: white; padding: 15px; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: bold;">{{ $exercisesCount }}</div>
            <div>Упражнений</div>
        </div>
        <div style="flex: 1; background: #e67e22; color: white; padding: 15px; border-radius: 8px; text-align: center;">
            <div style="font-size: 28px; font-weight: bold;">{{ $workoutsCount }}</div>
            <div>Тренировок</div>
        </div>
    </div>

    {{-- Сообщения об успехе/ошибке --}}
    @if (session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Вкладки --}}
    <div style="margin-bottom: 20px;">
        <button onclick="showTab('users')" class="tab-btn btn btn-primary" id="tab-users">👥 Пользователи</button>
        <button onclick="showTab('groups')" class="tab-btn btn" id="tab-groups" style="background: #95a5a6;">🏋️ Группы мышц</button>
        <button onclick="showTab('exercises')" class="tab-btn btn" id="tab-exercises" style="background: #95a5a6;">💪 Упражнения</button>
    </div>

    {{-- ============ ВКЛАДКА: ПОЛЬЗОВАТЕЛИ ============ --}}
    <div id="content-users" class="tab-content">
        <h3>Управление пользователями</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="border: 1px solid #ddd; padding: 10px;">ID</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Логин</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Текущая роль</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 10px;">{{ $user->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 10px;">{{ $user->login }}</td>
                        <td style="border: 1px solid #ddd; padding: 10px;">
                            <span style="background: {{ $user->role->name === 'admin' ? '#e74c3c' : ($user->role->name === 'trainer' ? '#f39c12' : '#3498db') }}; color: white; padding: 3px 8px; border-radius: 4px;">
                                {{ $user->role->name }}
                            </span>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 10px;">
                            <form action="{{ route('admin.assignRole', $user->id) }}" method="POST" style="display: flex; gap: 8px;">
                                @csrf
                                <select name="role_id" style="padding: 6px; border-radius: 4px; border: 1px solid #ddd;">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;">Применить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>

    {{-- ============ ВКЛАДКА: ГРУППЫ МЫШЦ ============ --}}
    <div id="content-groups" class="tab-content" style="display: none;">
        <h3>Справочник групп мышц</h3>
        
        {{-- Форма добавления --}}
        <form action="{{ route('admin.muscleGroups.store') }}" method="POST" style="display: flex; gap: 10px; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
            @csrf
            <input type="text" name="name" placeholder="Новая группа мышц (например: Ягодицы)" required style="flex: 1; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
            <button type="submit" class="btn btn-success">Добавить</button>
        </form>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="border: 1px solid #ddd; padding: 10px;">ID</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Название</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Кол-во упражнений</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($muscleGroups as $group)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 10px;">{{ $group->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 10px;">{{ $group->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 10px;">{{ $group->exercises_count }}</td>
                        <td style="border: 1px solid #ddd; padding: 10px;">
                            <form action="{{ route('admin.muscleGroups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 4px 8px;" onclick="return confirm('Удалить группу?')">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ============ ВКЛАДКА: УПРАЖНЕНИЯ ============ --}}
    <div id="content-exercises" class="tab-content" style="display: none;">
        <h3>Справочник упражнений</h3>
        
        {{-- Форма добавления --}}
        <form action="{{ route('admin.exercises.store') }}" method="POST" style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
            @csrf
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <input type="text" name="name" placeholder="Название упражнения" required style="flex: 2; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                <select name="muscle_group_id" required style="flex: 1; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
                    <option value="">-- Группа мышц --</option>
                    @foreach ($muscleGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <textarea name="description" placeholder="Описание техники (необязательно)" rows="2" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ddd; margin-bottom: 10px;"></textarea>
            <button type="submit" class="btn btn-success">Добавить упражнение</button>
        </form>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="border: 1px solid #ddd; padding: 10px;">ID</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Название</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Группа мышц</th>
                    <th style="border: 1px solid #ddd; padding: 10px;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($muscleGroups as $group)
                    @foreach ($group->exercises as $exercise)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 10px;">{{ $exercise->id }}</td>
                            <td style="border: 1px solid #ddd; padding: 10px;">{{ $exercise->name }}</td>
                            <td style="border: 1px solid #ddd; padding: 10px;">{{ $group->name }}</td>
                            <td style="border: 1px solid #ddd; padding: 10px;">
                                <form action="{{ route('admin.exercises.destroy', $exercise->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 4px 8px;" onclick="return confirm('Удалить упражнение?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function showTab(tabName) {
            // Скрываем все вкладки
            document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
            // Сбрасываем цвет кнопок
            document.querySelectorAll('.tab-btn').forEach(btn => btn.style.background = '#95a5a6');
            
            // Показываем нужную
            document.getElementById('content-' + tabName).style.display = 'block';
            document.getElementById('tab-' + tabName).style.background = '#3498db';
        }
        
        // По умолчанию показываем вкладку пользователей
        showTab('users');
    </script>
@endsection
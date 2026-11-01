<header>
    <div style="font-size: 20px; font-weight: bold;">🏋️ Workout Tracker</div>
    <nav>
        @auth
            <a href="{{ route('home') }}">Главная</a>
            <a href="{{ route('catalog') }}">Каталог упражнений</a>
            <a href="{{ route('workouts.index') }}">Мои тренировки</a>
            
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin') }}" style="color: #f1c40f;">Админ-панель</a>
            @endif

            <span style="margin-left: 15px; color: #bdc3c7;">{{ auth()->user()->login }} ({{ auth()->user()->role->name }})</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Выйти</button>
            </form>
        @else
            <a href="{{ route('login') }}">Войти</a>
            <a href="{{ route('register') }}">Регистрация</a>
        @endauth
    </nav>
</header>
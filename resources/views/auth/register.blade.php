@extends('layouts.app')
@section('title', 'Регистрация')
@section('content')
    <h2>Регистрация</h2>
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
        </div>
    @endif
    
    <form action="{{ route('register') }}" method="POST" style="max-width: 400px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Логин:</label><br>
            <input type="text" name="login" value="{{ old('login') }}" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Пароль:</label><br>
            <input type="password" name="password" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Подтверждение пароля:</label><br>
            <input type="password" name="password_confirmation" required style="width: 100%; padding: 8px;">
        </div>
        <button type="submit" class="btn btn-success">Зарегистрироваться</button>
    </form>
    <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
@endsection
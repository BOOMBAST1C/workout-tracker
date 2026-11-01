@extends('layouts.app')
@section('title', 'Вход')
@section('content')
    <h2>Вход в аккаунт</h2>
    @if ($errors->any()) <div style="color: red; margin-bottom: 15px;">{{ $errors->first() }}</div> @endif
    
    <form action="{{ route('login') }}" method="POST" style="max-width: 400px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label>Логин:</label><br>
            <input type="text" name="login" value="{{ old('login') }}" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Пароль:</label><br>
            <input type="password" name="password" required style="width: 100%; padding: 8px;">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
    <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
@endsection
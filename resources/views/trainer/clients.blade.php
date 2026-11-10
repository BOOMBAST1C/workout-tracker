@extends('layouts.app')

@section('title', 'Мои подопечные')

@section('content')
    <h2>👥 Мои подопечные</h2>
    
    @if ($clients->isEmpty())
        <p style="color: #666; margin-top: 20px;">У вас пока нет подопечных. Пользователи появятся здесь, когда вы проведёте для них тренировку.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Логин</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Роль</th>
                    <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 10px;">{{ $client->login }}</td>
                        <td style="border: 1px solid #ddd; padding: 10px;">
                            <span style="background: #3498db; color: white; padding: 3px 8px; border-radius: 4px; font-size: 12px;">
                                {{ $client->role->name }}
                            </span>
                        </td>
                        <td style="border: 1px solid #ddd; padding: 10px;">
                            <a href="{{ route('trainer.progress', $client->id) }}" class="btn btn-primary" style="font-size: 13px; padding: 6px 12px;">
                                📊 Смотреть прогресс
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
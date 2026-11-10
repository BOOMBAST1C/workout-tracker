@extends('layouts.app')

@section('title', 'Прогресс: ' . $client->login)

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('trainer.clients') }}" class="btn" style="background: #95a5a6; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px;">← Назад к подопечным</a>
    </div>

    <h2>📈 Динамика рабочих весов: {{ $client->login }}</h2>
    
    @if (count($datasets) === 0)
        <p style="color: #666; margin-top: 20px;">У этого подопечного пока нет записанных тренировок с весом.</p>
    @else
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-top: 20px;">
            <canvas id="progressChart" width="400" height="150"></canvas>
        </div>
    @endif

    {{-- Подключаем библиотеку Chart.js через CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('progressChart').getContext('2d');
        
        const chartData = {
            labels: @json($chartLabels),
            datasets: @json($datasets)
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Прогресс в ключевых упражнениях (максимальный вес, кг)' },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Вес (кг)' } }
                }
            }
        });
    </script>
@endsection
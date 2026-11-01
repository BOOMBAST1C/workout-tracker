<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Трекер тренировок')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #2c3e50; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        header nav a { color: white; text-decoration: none; margin-right: 15px; font-weight: bold; }
        header nav a:hover { text-decoration: underline; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        footer { text-align: center; padding: 20px; color: #777; margin-top: 40px; }
        .btn { padding: 8px 15px; border-radius: 4px; text-decoration: none; color: white; border: none; cursor: pointer; }
        .btn-primary { background: #3498db; }
        .btn-danger { background: #e74c3c; }
        .btn-success { background: #2ecc71; }
    </style>
</head>
<body>

    @include('partials.header')

    <div class="container">
        @if (session('success'))
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    @include('partials.footer')

</body>
</html>
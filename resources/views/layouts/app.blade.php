<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BimbelEdu') }} - @yield('title', '')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-body-main antialiased">
    @yield('content')

    @auth
    @include('chat.box')
    @include('chat.fab')
    @endauth

    @stack('scripts')
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            alert('{{ session('success') }}');
        });
    </script>
    @endif
    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            alert('{{ session('error') }}');
        });
    </script>
    @endif

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    window.Pusher = Pusher;
    const reverbKey = '{{ config("broadcasting.connections.reverb.key") }}';
    const reverbHost = window.location.hostname;
    const reverbPort = {{ config("broadcasting.connections.reverb.port") ?? 8080 }};

    window.reverbPusher = new Pusher(reverbKey, {
        wsHost: reverbHost,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        cluster: '',
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } },
    });


</script>
</body>
</html>

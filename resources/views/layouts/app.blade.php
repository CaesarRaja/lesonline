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

    document.addEventListener('DOMContentLoaded', function () {
        const broadcastChannel = window.reverbPusher.subscribe('broadcasts');
        broadcastChannel.bind('BroadcastSent', function (e) {
            const target = e.broadcast.target_role;
            const userRole = '{{ auth()->check() ? auth()->user()->role : "" }}';
            if (target === 'all' || target === userRole) {
                const container = document.createElement('div');
                container.className = 'fixed top-4 right-4 z-[100] bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl p-5 max-w-sm';
                container.innerHTML = '<div class="flex items-start gap-3"><div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary flex-shrink-0"><span class="material-symbols-outlined">campaign</span></div><div class="flex-1"><h4 class="font-label-bold text-label-bold text-on-surface">' + e.broadcast.judul + '</h4><p class="font-body-main text-body-main text-text-muted mt-1">' + e.broadcast.isi + '</p></div><button onclick="this.parentElement.parentElement.remove()" class="text-text-muted hover:text-on-surface"><span class="material-symbols-outlined text-[18px]">close</span></button></div>';
                document.body.appendChild(container);
                setTimeout(() => container.remove(), 10000);
            }
        });
    });
</script>
</body>
</html>

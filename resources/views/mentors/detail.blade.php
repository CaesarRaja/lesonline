@extends('layouts.app')

@section('title', $mentor->user->name)

@section('content')
<header class="bg-surface fixed top-0 w-full border-b border-outline-variant shadow-sm z-50">
    <div class="flex justify-between items-center px-margin-desktop max-w-container-max mx-auto h-16">
        <a class="font-display-logo text-display-logo text-primary" href="{{ route('landing') }}">BimbelEdu</a>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('mentors.index') }}" class="font-label-bold text-label-bold text-primary hover:text-primary-fixed transition-colors px-4 py-2">Kembali</a>
            @else
                <a href="{{ route('login') }}" class="font-label-bold text-label-bold bg-primary text-on-primary px-4 py-2 rounded-lg">Masuk untuk Memesan</a>
            @endauth
        </div>
    </div>
</header>

<main class="pt-24 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto py-8">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
        <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20 mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20 mb-4">{{ session('error') }}</div>
        @endif
        <div class="bg-surface rounded-2xl p-8 border border-outline-variant/30 shadow-sm">
            <div class="flex items-start gap-6 mb-6">
                <div class="w-20 h-20 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-display-logo text-2xl">
                    {{ strtoupper(substr($mentor->user->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <h1 class="font-display-logo text-2xl text-text-main font-extrabold">{{ $mentor->user->name }}</h1>
                    <p class="font-body-main text-body-main text-text-muted mt-1">{{ $mentor->keahlian ?? 'Mentor' }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="material-symbols-outlined text-secondary-container text-[20px] fill-icon">star</span>
                        <span class="font-label-bold text-label-bold text-text-main">{{ number_format($mentor->rating_rata_rata, 1) }}</span>
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    @auth
                        @if(auth()->user()->isStudent())
                        <button onclick="toggleChat({{ $mentor->user_id }}, '{{ $mentor->user->name }}')" class="flex items-center gap-1.5 px-3 py-1.5 bg-secondary-container text-on-secondary-container rounded-lg font-label-sm text-label-sm hover:bg-secondary-container/80 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">chat</span>
                            Chat
                        </button>
                        @endif
                        <form method="POST" action="{{ route('student.favorite.toggle', $mentor) }}" class="inline">
                            @csrf
                            <button class="flex items-center gap-1.5 px-3 py-1.5 border border-outline-variant rounded-lg font-label-sm text-label-sm hover:bg-surface-variant transition-colors">
                                <span class="material-symbols-outlined text-[16px] {{ $isFavorited ? 'text-error fill-icon' : 'text-text-muted' }}">favorite</span>
                                {{ $isFavorited ? 'Favorit' : 'Simpan' }}
                            </button>
                        </form>
                    @endauth
                </div>
                <div class="text-right">
                    <p class="font-label-sm text-label-sm text-text-muted">Tarif per Jam</p>
                    <p class="font-price-display text-price-display text-primary">Rp {{ number_format($mentor->tarif_per_jam, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="border-t border-outline-variant/30 pt-6">
                <h3 class="font-headline-card text-headline-card text-text-main mb-3">Bio</h3>
                <p class="font-body-main text-body-main text-text-muted">{{ $mentor->bio ?? 'Tidak ada bio.' }}</p>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="font-headline-card text-headline-card text-text-main mb-4">Jadwal Tersedia</h2>
            @if($schedules->isEmpty())
                <div class="bg-surface rounded-2xl p-8 border border-outline-variant/30 shadow-sm text-center">
                    <span class="material-symbols-outlined text-4xl text-text-muted mb-2">event_busy</span>
                    <p class="font-body-main text-body-main text-text-muted">Belum ada jadwal tersedia.</p>
                </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($schedules as $schedule)
                <div class="bg-surface rounded-xl p-5 border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-primary">calendar_month</span>
                        <span class="font-label-bold text-label-bold text-text-main">{{ $schedule->waktu_mulai->format('l, d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-text-muted">schedule</span>
                        <span class="font-body-main text-body-main text-text-muted">{{ $schedule->waktu_mulai->format('H:i') }} - {{ $schedule->waktu_selesai->format('H:i') }} WIB</span>
                    </div>
                    @auth
                        @if(auth()->user()->isStudent())
                        <form method="POST" action="{{ route('student.book', $schedule) }}">
                            @csrf
                            <button class="w-full bg-primary text-on-primary font-label-bold text-label-bold py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm" type="submit">Book Now</button>
                        </form>
                        @else
                        <button class="w-full bg-surface-container-high text-text-muted font-label-bold text-label-bold py-2.5 rounded-lg cursor-not-allowed" disabled>Login sebagai Student</button>
                        @endif
                    @else
                    <a href="{{ route('login') }}" class="block w-full bg-primary text-on-primary font-label-bold text-label-bold py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm text-center">Masuk untuk Memesan</a>
                    @endauth
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</main>

@auth
@if(auth()->user()->isStudent())
@include('chat.box')
@endif
@endauth


@endsection

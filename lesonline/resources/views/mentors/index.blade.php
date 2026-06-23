@extends('layouts.app')

@section('title', 'Cari Mentor')

@section('content')
<header class="bg-surface fixed top-0 w-full border-b border-outline-variant shadow-sm z-50">
    <div class="flex justify-between items-center px-margin-desktop max-w-container-max mx-auto h-16">
        <div class="flex items-center gap-8">
            <a class="font-display-logo text-display-logo text-primary" href="{{ route('landing') }}">BimbelEdu</a>
            <nav class="hidden md:flex gap-6 items-center h-full">
                <a class="text-primary border-b-2 border-primary pb-1 h-16 flex items-center mt-1 font-body-main text-body-main" href="{{ route('mentors.index') }}">Cari Mentor</a>
            </nav>
        </div>
        <div class="flex items-center gap-4">
            @auth
                @php
                    $route = match(auth()->user()->role) {
                        'admin' => 'admin.dashboard',
                        'mentor' => 'mentor.dashboard',
                        default => 'student.dashboard',
                    };
                @endphp
                <a href="{{ route($route) }}" class="font-label-bold text-label-bold bg-primary text-on-primary px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors shadow-sm">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-label-bold text-label-bold text-primary hover:text-primary-fixed transition-colors px-4 py-2">Masuk</a>
                <a href="{{ route('register') }}" class="font-label-bold text-label-bold bg-primary text-on-primary px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors shadow-sm">Daftar</a>
            @endauth
        </div>
    </div>
</header>

<main class="flex-grow flex flex-col items-center w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8 md:py-12 gap-12 pt-24">
    <section class="w-full max-w-4xl flex flex-col items-center text-center gap-6 mt-8">
        <h1 class="font-display-logo text-4xl md:text-5xl text-text-main tracking-tight leading-tight">Temukan mentor terbaik untuk mengembangkan skillmu.</h1>
        <p class="font-body-main text-lg text-text-muted max-w-2xl">Terhubung dengan pengajar ahli di bidang programming, bahasa, desain, dan lainnya.</p>
        <form method="GET" action="{{ route('mentors.index') }}" class="w-full max-w-3xl mt-4 bg-surface p-2 rounded-[20px] shadow-sm border border-outline-variant/30 flex flex-col md:flex-row gap-2">
            <div class="flex-1 relative flex items-center border-b md:border-b-0 md:border-r border-outline-variant/30 p-2">
                <span class="material-symbols-outlined text-primary ml-2 mr-3">search</span>
                <input class="w-full bg-transparent border-none focus:ring-0 text-body-main font-body-main placeholder:text-text-muted p-0" name="search" placeholder="Apa yang ingin kamu pelajari? (contoh: Python, TOEFL)" value="{{ request('search') }}" type="text">
            </div>
            <button class="bg-secondary-container text-on-secondary-container font-label-bold text-label-bold px-8 py-3 md:py-4 rounded-xl hover:bg-secondary-container/90 transition-all shadow-sm whitespace-nowrap m-1" type="submit">Cari Mentor</button>
        </form>
        <div class="flex flex-wrap justify-center gap-3 mt-4">
            <span class="text-label-sm font-label-sm text-text-muted mr-2 flex items-center">Populer:</span>
            <a href="{{ route('mentors.index', ['search' => 'Python']) }}" class="px-3 py-1 bg-surface-container-high rounded-full text-label-sm font-label-sm text-on-surface-variant cursor-pointer hover:bg-primary-fixed hover:text-on-primary-fixed transition-colors">Python</a>
            <a href="{{ route('mentors.index', ['search' => 'IELTS']) }}" class="px-3 py-1 bg-surface-container-high rounded-full text-label-sm font-label-sm text-on-surface-variant cursor-pointer hover:bg-primary-fixed hover:text-on-primary-fixed transition-colors">IELTS</a>
            <a href="{{ route('mentors.index', ['search' => 'Desain']) }}" class="px-3 py-1 bg-surface-container-high rounded-full text-label-sm font-label-sm text-on-surface-variant cursor-pointer hover:bg-primary-fixed hover:text-on-primary-fixed transition-colors">Desain</a>
            <a href="{{ route('mentors.index', ['search' => 'Data Science']) }}" class="px-3 py-1 bg-surface-container-high rounded-full text-label-sm font-label-sm text-on-surface-variant cursor-pointer hover:bg-primary-fixed hover:text-on-primary-fixed transition-colors">Data Science</a>
        </div>
    </section>

    <section class="w-full">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-headline-card text-headline-card text-text-main">Daftar Mentor</h2>
        </div>
        @if($mentors->isEmpty())
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-text-muted mb-4">search_off</span>
                <p class="font-body-main text-body-main text-text-muted">Tidak ada mentor ditemukan.</p>
            </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-gutter w-full">
            @foreach($mentors as $mentor)
            <div class="bg-surface rounded-2xl p-5 border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full group">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-headline-card text-headline-card">
                        {{ strtoupper(substr($mentor->user->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-headline-card text-headline-card text-text-main leading-tight group-hover:text-primary transition-colors">{{ $mentor->user->name }}</h3>
                        <p class="font-body-main text-body-main text-text-muted text-sm mt-0.5 line-clamp-1">{{ $mentor->keahlian ?? 'Mentor' }}</p>
                        <div class="flex items-center gap-1 mt-1.5">
                            <span class="material-symbols-outlined text-secondary-container text-[16px] fill-icon">star</span>
                            <span class="font-label-bold text-label-bold text-text-main text-sm">{{ number_format($mentor->rating_rata_rata, 1) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mb-4 flex-grow">
                    @foreach(explode(',', $mentor->keahlian ?? 'Umum') as $skill)
                        @if(trim($skill))
                        <span class="px-2.5 py-1 bg-surface-container rounded text-label-sm font-label-sm text-on-surface-variant">{{ trim($skill) }}</span>
                        @endif
                    @endforeach
                </div>
                <div class="pt-4 border-t border-outline-variant/30 flex justify-between items-center mt-auto">
                    <div class="flex flex-col">
                        <span class="font-label-sm text-label-sm text-text-muted">Tarif</span>
                        <span class="font-price-display text-price-display text-text-main">Rp {{ number_format($mentor->tarif_per_jam, 0, ',', '.') }}<span class="font-body-main text-body-main text-text-muted font-normal">/jam</span></span>
                    </div>
                    <a href="{{ route('mentors.detail', $mentor) }}" class="bg-primary text-on-primary font-label-bold text-label-bold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors shadow-sm">Pesan</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>
</main>

<footer class="bg-surface-container-lowest w-full border-t border-outline-variant mt-12">
    <div class="max-w-container-max mx-auto px-margin-desktop py-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="font-display-logo text-display-logo text-primary">BimbelEdu</div>
        <div class="flex gap-6 items-center flex-wrap justify-center">
            <a class="font-label-sm text-label-sm text-text-muted hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
            <a class="font-label-sm text-label-sm text-text-muted hover:text-primary transition-colors" href="#">Syarat & Ketentuan</a>
            <a class="font-label-sm text-label-sm text-text-muted hover:text-primary transition-colors" href="#">Kontak</a>
        </div>
        <div class="font-label-sm text-label-sm text-text-muted">&copy; {{ date('Y') }} BimbelEdu</div>
    </div>
</footer>
@endsection

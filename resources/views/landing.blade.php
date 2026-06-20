@extends('layouts.app')

@section('title', 'Temukan Mentor Terbaik')

@section('content')
<nav class="bg-surface fixed top-0 w-full border-b border-outline-variant shadow-sm z-50">
    <div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto h-16">
        <div class="flex items-center gap-8">
            <a class="font-display-logo text-display-logo text-primary" href="{{ route('landing') }}">BimbelEdu</a>
            <div class="hidden md:flex gap-6">
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="{{ route('mentors.index') }}">Cari Mentor</a>
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="#">Cara Kerja</a>
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="#">Tentang</a>
            </div>
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
</nav>

<main class="pt-24 pb-16 px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
    <section class="flex flex-col md:flex-row items-center gap-12 py-12 md:py-20">
        <div class="flex-1 space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-high border border-outline-variant text-label-sm font-label-sm text-on-surface-variant mb-4">
                <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                Platform Edukasi Modern
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-display-logo font-extrabold text-text-main leading-tight tracking-tight">
                Temukan Mentor Terbaik untuk <span class="text-primary">Masa Depanmu</span>
            </h1>
            <p class="text-lg text-on-surface-variant max-w-xl leading-relaxed">
                Tingkatkan skillmu dengan ahli di bidangnya. Mulai dari Python, Kalkulus tingkat lanjut, hingga persiapan TOEFL intensif. Belajar langsung, interaktif, dan fleksibel.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="{{ route('mentors.index') }}" class="bg-primary text-on-primary font-label-bold text-label-bold px-8 py-3 rounded-xl hover:bg-primary-container transition-all shadow-md flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">search</span>
                    Cari Mentor Sekarang
                </a>
                <a href="{{ route('register') }}" class="border-2 border-primary text-primary font-label-bold text-label-bold px-8 py-3 rounded-xl hover:bg-surface-container transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">school</span>
                    Gabung Jadi Mentor
                </a>
            </div>
            <div class="flex items-center gap-4 pt-8 border-t border-outline-variant mt-8">
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full border-2 border-surface bg-primary-fixed"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-surface bg-secondary-fixed"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-surface bg-primary-fixed-dim"></div>
                </div>
                <div class="text-sm font-body-main text-on-surface-variant">
                    <span class="font-bold text-text-main">{{ number_format($mentorCount) }}+</span> mentor terpercaya bergabung
                </div>
            </div>
        </div>
        <div class="flex-1 relative w-full h-[500px]">
            <div class="absolute inset-0 bg-primary-fixed-dim rounded-3xl opacity-20 transform rotate-3"></div>
            <div class="relative z-10 w-full h-full bg-cover bg-center rounded-2xl shadow-xl border border-surface-container-highest" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&q=80')"></div>
            <div class="absolute top-10 -left-6 bg-surface p-4 rounded-xl shadow-lg border border-surface-container-highest flex items-center gap-3 z-20">
                <div class="w-10 h-10 rounded-full bg-success-bg flex items-center justify-center text-success-text">
                    <span class="material-symbols-outlined fill-icon">verified</span>
                </div>
                <div>
                    <div class="font-label-bold text-label-bold text-text-main">Verified Mentors</div>
                    <div class="font-label-sm text-label-sm text-text-muted">Kualitas Terjamin</div>
                </div>
            </div>
            <div class="absolute bottom-10 -right-6 bg-surface p-4 rounded-xl shadow-lg border border-surface-container-highest flex items-center gap-3 z-20">
                <div class="w-10 h-10 rounded-full bg-pending-bg flex items-center justify-center text-pending-text">
                    <span class="material-symbols-outlined">schedule</span>
                </div>
                <div>
                    <div class="font-label-bold text-label-bold text-text-main">Sesi Fleksibel</div>
                    <div class="font-label-sm text-label-sm text-text-muted">Atur jadwalmu sendiri</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="text-center mb-12">
            <h2 class="font-headline-card text-headline-card text-2xl text-text-main mb-4">Mengapa Belajar di BimbelEdu?</h2>
            <p class="font-body-main text-body-main text-on-surface-variant max-w-2xl mx-auto">Kami merancang platform yang memudahkan interaksi antara mentor dan murid untuk pengalaman belajar yang optimal.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-primary-container/10 rounded-xl flex items-center justify-center text-primary mb-6">
                    <span class="material-symbols-outlined text-2xl">video_camera_front</span>
                </div>
                <h3 class="font-headline-card text-headline-card text-text-main mb-3">Sesi Real-time</h3>
                <p class="font-body-main text-body-main text-on-surface-variant">Belajar langsung tatap muka secara virtual. Diskusi lebih hidup, pertanyaan terjawab saat itu juga.</p>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-secondary-container/20 rounded-xl flex items-center justify-center text-secondary mb-6">
                    <span class="material-symbols-outlined text-2xl">verified_user</span>
                </div>
                <h3 class="font-headline-card text-headline-card text-text-main mb-3">Mentor Terverifikasi</h3>
                <p class="font-body-main text-body-main text-on-surface-variant">Setiap mentor melewati proses seleksi ketat untuk memastikan mereka benar-benar ahli di bidangnya.</p>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-success-bg rounded-xl flex items-center justify-center text-success-text mb-6">
                    <span class="material-symbols-outlined text-2xl">lock</span>
                </div>
                <h3 class="font-headline-card text-headline-card text-text-main mb-3">Pembayaran Aman</h3>
                <p class="font-body-main text-body-main text-on-surface-variant">Sistem pembayaran terintegrasi Midtrans yang menjamin keamanan dana Anda hingga sesi selesai.</p>
            </div>
        </div>
    </section>
</main>

<footer class="bg-surface-container-lowest border-t border-outline-variant w-full">
    <div class="max-w-container-max mx-auto px-margin-desktop py-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-2">
            <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
        </div>
        <div class="flex flex-wrap justify-center gap-6">
            <a class="font-label-sm text-label-sm text-text-muted hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
            <a class="font-label-sm text-label-sm text-text-muted hover:text-primary transition-colors" href="#">Syarat & Ketentuan</a>
            <a class="font-label-sm text-label-sm text-text-muted hover:text-primary transition-colors" href="#">Kontak</a>
        </div>
        <div class="font-label-sm text-label-sm text-text-muted">
            &copy; {{ date('Y') }} BimbelEdu. All rights reserved.
        </div>
    </div>
</footer>
@endsection

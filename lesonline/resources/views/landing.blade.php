@extends('layouts.app')

@section('title', 'Temukan Mentor Terbaik')

@section('content')
<nav class="bg-surface fixed top-0 w-full border-b border-outline-variant shadow-sm z-50">
    <div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto h-16">
        <div class="flex items-center gap-8">
            <a class="font-display-logo text-display-logo text-primary" href="{{ route('landing') }}">BimbelEdu</a>
            <div class="hidden md:flex gap-6">
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="{{ route('mentors.index') }}">Cari Mentor</a>
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="#program">Program</a>
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="#testimoni">Testimoni</a>
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="#faq">FAQ</a>
                <a class="font-body-main text-body-main text-on-surface-variant hover:text-primary transition-colors" href="#kontak">Kontak</a>
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

    <section class="py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto text-center">
            <div>
                <div class="text-4xl font-bold text-primary">500+</div>
                <div class="font-body-main text-body-main text-text-muted mt-1">Siswa Aktif</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-secondary">50+</div>
                <div class="font-body-main text-body-main text-text-muted mt-1">Mentor Ahli</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-primary">1000+</div>
                <div class="font-body-main text-body-main text-text-muted mt-1">Sesi Terselesaikan</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-secondary">4.9</div>
                <div class="font-body-main text-body-main text-text-muted mt-1">Rating Rata-rata</div>
            </div>
        </div>
    </section>

    <section id="program" class="py-16">
        <div class="text-center mb-12">
            <h2 class="font-headline-card text-headline-card text-2xl text-text-main mb-4">Program Belajar</h2>
            <p class="font-body-main text-body-main text-on-surface-variant max-w-2xl mx-auto">Berbagai bidang studi yang siap membantu kamu mencapai target akademik dan profesional.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-primary-container/10 rounded-xl flex items-center justify-center text-primary mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">calculate</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-text-main">Matematika</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-secondary-container/20 rounded-xl flex items-center justify-center text-secondary mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">science</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-text-main">Fisika</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-success-bg rounded-xl flex items-center justify-center text-success-text mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">biotech</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-text-main">Kimia</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-pending-bg rounded-xl flex items-center justify-center text-pending-text mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">language</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-text-main">TOEFL</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-primary-container/10 rounded-xl flex items-center justify-center text-primary mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">code</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-text-main">Programming</h3>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant text-center hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-secondary-container/20 rounded-xl flex items-center justify-center text-secondary mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">account_balance</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-text-main">Ekonomi</h3>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-16">
        <div class="text-center mb-12">
            <h2 class="font-headline-card text-headline-card text-2xl text-text-main mb-4">Apa Kata Mereka</h2>
            <p class="font-body-main text-body-main text-on-surface-variant max-w-2xl mx-auto">Pengalaman nyata dari siswa yang sudah belajar di BimbelEdu.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                <div class="flex items-center gap-1 text-yellow-500 mb-3">
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                </div>
                <p class="font-body-main text-body-main text-on-surface-variant mb-4 italic">"Mentornya sabar banget jelasin konsep Kalkulus. Sekarang aku jauh lebih paham dan nilai UTS naik drastis!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-label-bold">A</div>
                    <div>
                        <div class="font-label-bold text-label-bold text-text-main">Andi Pratama</div>
                        <div class="font-label-sm text-label-sm text-text-muted">Siswa Matematika</div>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                <div class="flex items-center gap-1 text-yellow-500 mb-3">
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                </div>
                <p class="font-body-main text-body-main text-on-surface-variant mb-4 italic">"Persiapan TOEFL jadi lebih terarah dengan mentor yang berpengalaman. Skor saya naik dari 80 ke 105 dalam 2 bulan!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary font-label-bold">S</div>
                    <div>
                        <div class="font-label-bold text-label-bold text-text-main">Siti Rahmawati</div>
                        <div class="font-label-sm text-label-sm text-text-muted">Siswa TOEFL</div>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant shadow-sm">
                <div class="flex items-center gap-1 text-yellow-500 mb-3">
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star</span>
                    <span class="material-symbols-outlined text-lg">star_half</span>
                </div>
                <p class="font-body-main text-body-main text-on-surface-variant mb-4 italic">"Jadwal fleksibel banget, cocok buat aku yang sambil kerja. Mentornya juga paham banget soal Python & Data Science."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-label-bold">R</div>
                    <div>
                        <div class="font-label-bold text-label-bold text-text-main">Rizky Hidayat</div>
                        <div class="font-label-sm text-label-sm text-text-muted">Siswa Programming</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-16" x-data="{ open: null }">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="font-headline-card text-headline-card text-2xl text-text-main mb-4">Pertanyaan Umum</h2>
                <p class="font-body-main text-body-main text-on-surface-variant max-w-2xl mx-auto">Temukan jawaban cepat seputar platform kami.</p>
            </div>
            <div class="space-y-3">
                <div class="rounded-2xl border transition-all duration-200 overflow-hidden"
                    :class="open === 1 ? 'border-primary bg-primary-container/5 shadow-md' : 'border-outline-variant bg-surface-container-lowest shadow-sm hover:bg-surface-container-high hover:shadow'">
                    <button @click="open = open === 1 ? null : 1"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-container-lowest rounded-2xl">
                        <span class="font-label-bold text-label-bold pr-4" :class="open === 1 ? 'text-primary' : 'text-text-main'">Bagaimana cara mendaftar?</span>
                        <span class="material-symbols-outlined transition-transform duration-300 flex-shrink-0" :class="open === 1 ? 'rotate-180 text-primary' : 'text-text-muted'">expand_more</span>
                    </button>
                    <div x-show="open === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak>
                        <div class="px-5 pb-5">
                            <p class="font-body-main text-body-main text-on-surface-variant">Klik tombol "Daftar", isi data diri, pilih peran sebagai Siswa atau Mentor. Setelah verifikasi email, kamu sudah bisa langsung menggunakan platform.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border transition-all duration-200 overflow-hidden"
                    :class="open === 2 ? 'border-primary bg-primary-container/5 shadow-md' : 'border-outline-variant bg-surface-container-lowest shadow-sm hover:bg-surface-container-high hover:shadow'">
                    <button @click="open = open === 2 ? null : 2"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-container-lowest rounded-2xl">
                        <span class="font-label-bold text-label-bold pr-4" :class="open === 2 ? 'text-primary' : 'text-text-main'">Bagaimana sistem pembayarannya?</span>
                        <span class="material-symbols-outlined transition-transform duration-300 flex-shrink-0" :class="open === 2 ? 'rotate-180 text-primary' : 'text-text-muted'">expand_more</span>
                    </button>
                    <div x-show="open === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak>
                        <div class="px-5 pb-5">
                            <p class="font-body-main text-body-main text-on-surface-variant">Pembayaran dilakukan melalui Midtrans yang mendukung transfer bank, kartu kredit, dan e-wallet. Dana akan ditahan hingga sesi selesai, sehingga aman bagi kedua pihak.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border transition-all duration-200 overflow-hidden"
                    :class="open === 3 ? 'border-primary bg-primary-container/5 shadow-md' : 'border-outline-variant bg-surface-container-lowest shadow-sm hover:bg-surface-container-high hover:shadow'">
                    <button @click="open = open === 3 ? null : 3"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-container-lowest rounded-2xl">
                        <span class="font-label-bold text-label-bold pr-4" :class="open === 3 ? 'text-primary' : 'text-text-main'">Berapa biaya belajarnya?</span>
                        <span class="material-symbols-outlined transition-transform duration-300 flex-shrink-0" :class="open === 3 ? 'rotate-180 text-primary' : 'text-text-muted'">expand_more</span>
                    </button>
                    <div x-show="open === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak>
                        <div class="px-5 pb-5">
                            <p class="font-body-main text-body-main text-on-surface-variant">Setiap mentor menetapkan tarifnya sendiri. Kamu bisa melihat tarif per sesi di profil mentor dan memilih yang sesuai dengan budgetmu.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border transition-all duration-200 overflow-hidden"
                    :class="open === 4 ? 'border-primary bg-primary-container/5 shadow-md' : 'border-outline-variant bg-surface-container-lowest shadow-sm hover:bg-surface-container-high hover:shadow'">
                    <button @click="open = open === 4 ? null : 4"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-container-lowest rounded-2xl">
                        <span class="font-label-bold text-label-bold pr-4" :class="open === 4 ? 'text-primary' : 'text-text-main'">Bisa ganti mentor jika tidak cocok?</span>
                        <span class="material-symbols-outlined transition-transform duration-300 flex-shrink-0" :class="open === 4 ? 'rotate-180 text-primary' : 'text-text-muted'">expand_more</span>
                    </button>
                    <div x-show="open === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak>
                        <div class="px-5 pb-5">
                            <p class="font-body-main text-body-main text-on-surface-variant">Tentu. Kamu bisa mencari mentor lain kapan saja. Setiap mentor memiliki profil lengkap dengan rating dan ulasan untuk membantu kamu memilih.</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl border transition-all duration-200 overflow-hidden"
                    :class="open === 5 ? 'border-primary bg-primary-container/5 shadow-md' : 'border-outline-variant bg-surface-container-lowest shadow-sm hover:bg-surface-container-high hover:shadow'">
                    <button @click="open = open === 5 ? null : 5"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-surface-container-lowest rounded-2xl">
                        <span class="font-label-bold text-label-bold pr-4" :class="open === 5 ? 'text-primary' : 'text-text-main'">Apa itu sesi real-time?</span>
                        <span class="material-symbols-outlined transition-transform duration-300 flex-shrink-0" :class="open === 5 ? 'rotate-180 text-primary' : 'text-text-muted'">expand_more</span>
                    </button>
                    <div x-show="open === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak>
                        <div class="px-5 pb-5">
                            <p class="font-body-main text-body-main text-on-surface-variant">Sesi real-time adalah pertemuan belajar langsung antara kamu dan mentor melalui video call. Kamu bisa bertanya, diskusi, dan belajar interaktif seperti tatap muka.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="bg-surface-container-lowest border-t border-outline-variant w-full">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            <div>
                <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
                <p class="font-body-main text-body-main text-text-muted mt-2 max-w-xs">Platform bimbingan belajar online yang menghubungkan siswa dengan mentor terbaik di Indonesia.</p>
            </div>
            <div>
                <h3 class="font-label-bold text-label-bold text-text-main mb-3">Hubungi Kami</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-text-muted">
                        <span class="material-symbols-outlined text-[16px] flex-shrink-0">mail</span>
                        <span class="font-body-main text-body-main">support@bimbeledu.com</span>
                    </div>
                    <div class="flex items-center gap-2 text-text-muted">
                        <span class="material-symbols-outlined text-[16px] flex-shrink-0">call</span>
                        <span class="font-body-main text-body-main">+62 812-3456-7890</span>
                    </div>
                    <div class="flex items-center gap-2 text-text-muted">
                        <span class="material-symbols-outlined text-[16px] flex-shrink-0">location_on</span>
                        <span class="font-body-main text-body-main">Jl. Pendidikan No. 123, Jakarta Pusat</span>
                    </div>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="font-label-sm text-label-sm text-text-muted">&copy; {{ date('Y') }} BimbelEdu. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
@endsection

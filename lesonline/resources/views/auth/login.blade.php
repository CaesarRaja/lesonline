@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="min-h-screen flex">
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-margin-mobile py-12 lg:px-margin-desktop bg-surface relative z-10 shadow-sm lg:shadow-none">
        <div class="w-full max-w-md space-y-8">
            <div class="flex items-center gap-2 mb-12">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary">
                    <span class="material-symbols-outlined text-xl fill-icon">menu_book</span>
                </div>
                <span class="font-display-logo text-display-logo text-primary tracking-tight">BimbelEdu</span>
            </div>
            <div class="space-y-2">
                <h1 class="font-display-logo text-[28px] leading-tight font-extrabold text-text-main">
                    Selamat Datang Kembali
                </h1>
                <p class="font-body-main text-body-main text-text-muted">
                    Silakan masukkan detail Anda untuk mengakses akun.
                </p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="block font-label-bold text-label-bold text-text-main" for="email">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-muted">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                        </div>
                        <input autocomplete="email" class="block w-full pl-10 pr-3 py-3 border border-outline-variant rounded-xl bg-background text-text-main font-body-main text-body-main placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-shadow" id="email" name="email" placeholder="nama@email.com" required type="email" value="{{ old('email') }}">
                    </div>
                    @error('email') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="block font-label-bold text-label-bold text-text-main" for="password">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-text-muted">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                        </div>
                        <input autocomplete="current-password" class="block w-full pl-10 pr-10 py-3 border border-outline-variant rounded-xl bg-background text-text-main font-body-main text-body-main placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-shadow" id="password" name="password" placeholder="••••••••" required type="password">
                    </div>
                    @error('password') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-primary focus:ring-primary border-outline-variant rounded cursor-pointer" id="remember_me" name="remember" type="checkbox">
                        <label class="ml-2 block font-label-sm text-label-sm text-text-muted cursor-pointer" for="remember_me">Remember Me</label>
                    </div>
                    @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a class="font-label-sm text-label-sm text-primary hover:text-primary-container font-semibold transition-colors" href="{{ route('password.request') }}">Lupa Password?</a>
                    </div>
                    @endif
                </div>
                <div>
                    <button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm font-label-bold text-label-bold text-on-primary bg-primary hover:bg-primary-container focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all active:scale-[0.98]" type="submit">
                        Masuk
                    </button>
                </div>
            </form>
            <p class="mt-8 text-center font-body-main text-body-main text-text-muted">
                Belum punya akun?
                <a class="font-label-bold text-label-bold text-primary hover:text-primary-container transition-colors ml-1" href="{{ route('register') }}">Daftar sekarang</a>
            </p>
        </div>
    </div>
    <div class="hidden lg:block lg:w-1/2 relative bg-surface-container-low overflow-hidden">
        <div class="absolute top-0 right-0 w-[40rem] h-[40rem] bg-primary-fixed rounded-full blur-3xl opacity-30 -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-secondary-fixed rounded-full blur-3xl opacity-20 translate-y-1/3 -translate-x-1/4"></div>
        <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&q=80')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-text-main/60 via-transparent to-transparent"></div>
        <div class="absolute bottom-12 left-12 right-12">
            <div class="bg-surface/90 backdrop-blur-md p-6 rounded-2xl shadow-lg border border-surface-variant max-w-md">
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                        <span class="material-symbols-outlined text-[20px] fill-icon">school</span>
                    </div>
                    <div>
                        <p class="font-label-bold text-label-bold text-text-main">Platform Belajar #1</p>
                        <p class="font-label-sm text-label-sm text-text-muted">Dipercaya oleh 10,000+ Siswa</p>
                    </div>
                </div>
                <p class="font-body-main text-body-main text-text-main italic">"BimbelEdu membantu saya menemukan mentor terbaik yang sesuai dengan gaya belajar saya."</p>
            </div>
        </div>
    </div>
</div>
@endsection

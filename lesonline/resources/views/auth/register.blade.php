@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen flex">
    <div class="hidden lg:flex lg:w-1/2 relative bg-surface-container overflow-hidden items-center justify-center">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&q=80')"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-primary/80 to-transparent mix-blend-multiply"></div>
        <div class="relative z-10 p-12 text-on-primary max-w-lg text-center">
            <h2 class="font-display-logo text-[32px] leading-tight font-extrabold mb-4">Empowering the next generation of learners.</h2>
            <p class="font-body-main text-body-main text-primary-fixed opacity-90">Join our community of mentors and students to accelerate your educational journey with real-time, interactive sessions.</p>
        </div>
    </div>
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-margin-mobile sm:px-12 md:px-24 py-12 bg-surface-container-lowest overflow-y-auto">
        <div class="max-w-[440px] w-full mx-auto">
            <div class="mb-10 text-center lg:text-left">
                <a class="inline-flex items-center gap-2 mb-8" href="{{ route('landing') }}">
                    <span class="material-symbols-outlined text-primary text-[32px] fill-icon">menu_book</span>
                    <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
                </a>
                <h1 class="font-display-logo text-[28px] leading-tight font-extrabold text-text-main mb-2">Bergabung dengan BimbelEdu</h1>
                <p class="font-body-main text-body-main text-text-muted">Mulai perjalanan belajar atau mengajar Anda hari ini</p>
            </div>
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div class="space-y-3">
                    <label class="font-label-bold text-label-bold text-text-main block">Pilih Peran Anda</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer group">
                            <input checked class="peer sr-only" name="role" type="radio" value="student">
                            <div class="rounded-xl border-2 border-primary bg-primary-fixed/30 p-4 text-center transition-all duration-200 shadow-sm peer-checked:border-primary peer-checked:bg-primary-fixed/30 hover:shadow-md">
                                <span class="material-symbols-outlined text-primary mb-2 text-[28px] fill-icon">school</span>
                                <p class="font-label-bold text-label-bold text-primary">Student</p>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input class="peer sr-only" name="role" type="radio" value="mentor">
                            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-4 text-center transition-all duration-200 hover:border-primary hover:shadow-md peer-checked:border-2 peer-checked:border-primary peer-checked:bg-primary-fixed/30 shadow-sm">
                                <span class="material-symbols-outlined text-outline group-hover:text-primary mb-2 text-[28px]">workspace_premium</span>
                                <p class="font-label-bold text-label-bold text-text-main group-hover:text-primary">Mentor</p>
                            </div>
                        </label>
                    </div>
                    @error('role') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="font-label-bold text-label-bold text-text-main block mb-1.5" for="name">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[20px]">person</span>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant bg-surface-container-lowest font-body-main text-body-main text-text-main focus:border-primary focus:ring-2 focus:ring-primary-fixed focus:outline-none transition-all shadow-sm" id="name" name="name" placeholder="John Doe" type="text" value="{{ old('name') }}" required>
                        </div>
                        @error('name') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-bold text-label-bold text-text-main block mb-1.5" for="email">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[20px]">mail</span>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant bg-surface-container-lowest font-body-main text-body-main text-text-main focus:border-primary focus:ring-2 focus:ring-primary-fixed focus:outline-none transition-all shadow-sm" id="email" name="email" placeholder="nama@email.com" type="email" value="{{ old('email') }}" required>
                        </div>
                        @error('email') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-bold text-label-bold text-text-main block mb-1.5" for="password">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[20px]">lock</span>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant bg-surface-container-lowest font-body-main text-body-main text-text-main focus:border-primary focus:ring-2 focus:ring-primary-fixed focus:outline-none transition-all shadow-sm" id="password" name="password" placeholder="••••••••" type="password" required>
                        </div>
                        @error('password') <p class="text-error text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="font-label-bold text-label-bold text-text-main block mb-1.5" for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="material-symbols-outlined text-outline text-[20px]">lock</span>
                            </div>
                            <input class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant bg-surface-container-lowest font-body-main text-body-main text-text-main focus:border-primary focus:ring-2 focus:ring-primary-fixed focus:outline-none transition-all shadow-sm" id="password_confirmation" name="password_confirmation" placeholder="••••••••" type="password" required>
                        </div>
                    </div>
                </div>
                <button class="w-full py-3.5 px-4 rounded-xl bg-primary text-on-primary font-label-bold text-label-bold shadow-sm hover:shadow-md hover:bg-surface-tint transition-all active:scale-[0.98] flex items-center justify-center gap-2" type="submit">
                    Daftar Sekarang
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>
            </form>
            <p class="mt-8 text-center font-body-main text-body-main text-text-muted">
                Sudah punya akun? <a class="font-label-bold text-label-bold text-primary hover:underline underline-offset-4" href="{{ route('login') }}">Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[name="role"]').forEach(function (input) {
            input.addEventListener('change', function (e) {
                document.querySelectorAll('input[name="role"]').forEach(function (r) {
                    var container = r.nextElementSibling;
                    var icon = container.querySelector('.material-symbols-outlined');
                    var text = container.querySelector('p');
                    container.classList.remove('border-2', 'border-primary', 'bg-primary-fixed/30');
                    container.classList.add('border', 'border-outline-variant', 'bg-surface-container-lowest');
                    icon.classList.remove('text-primary');
                    icon.classList.add('text-outline');
                    text.classList.remove('text-primary');
                    text.classList.add('text-text-main', 'group-hover:text-primary');
                });
                if (e.target.checked) {
                    var container = e.target.nextElementSibling;
                    var icon = container.querySelector('.material-symbols-outlined');
                    var text = container.querySelector('p');
                    container.classList.remove('border', 'border-outline-variant', 'bg-surface-container-lowest');
                    container.classList.add('border-2', 'border-primary', 'bg-primary-fixed/30');
                    icon.classList.remove('text-outline');
                    icon.classList.add('text-primary');
                    icon.style.fontVariationSettings = "'FILL' 1";
                    text.classList.remove('text-text-main', 'group-hover:text-primary');
                    text.classList.add('text-primary');
                }
            });
        });
    });
</script>
@endpush

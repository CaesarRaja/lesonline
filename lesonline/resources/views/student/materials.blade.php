@extends('layouts.app')

@section('title', 'Materi Saya')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 md:hidden"></div>

    <aside class="fixed left-0 h-full w-sidebar-width bg-surface-container border-r border-outline-variant flex flex-col p-4 z-40 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
        <div class="mb-8 px-2 flex flex-col gap-1">
            <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Student Portal</span>
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-3 py-2 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined">folder</span>
                Materi Saya
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.payments') }}">
                <span class="material-symbols-outlined">receipt_long</span>
                Pembayaran
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentors.index') }}">
                <span class="material-symbols-outlined">search</span>
                Cari Mentor
            </a>
        </nav>
        <div class="mt-auto mb-6 px-2">
            <a href="{{ route('mentors.index') }}" class="w-full py-2 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                Pesan Sesi Baru
            </a>
        </div>
        <div class="border-t border-outline-variant pt-4 flex flex-col gap-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors w-full text-left" type="submit">
                    <span class="material-symbols-outlined">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto">
        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-6">

            {{-- Mobile header with hamburger --}}
            <div class="flex items-center gap-4 md:hidden mb-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
                    <span class="material-symbols-outlined" x-show="!sidebarOpen">menu</span>
                    <span class="material-symbols-outlined" x-show="sidebarOpen" x-cloak>close</span>
                </button>
                <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Materi Pembelajaran</h1>
            </div>
            <h1 class="hidden md:block font-display-logo text-2xl text-text-main font-extrabold">Materi Pembelajaran</h1>

            @php
                $groupedGeneral = $generalMaterials->groupBy(fn($m) => $m->mentor->user->name);
            @endphp

            @if($groupedGeneral->isNotEmpty())
                @foreach($groupedGeneral as $mentorName => $materials)
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-5">
                    <h2 class="font-headline-card text-headline-card text-text-main mb-3">{{ $mentorName }} - Materi Umum</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($materials as $material)
                        <div class="bg-surface rounded-lg border border-outline-variant p-3 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-3xl">description</span>
                            <div class="flex-1 min-w-0">
                                <p class="font-label-bold text-label-bold text-text-main truncate">{{ $material->judul }}</p>
                                <p class="font-label-sm text-label-sm text-text-muted">{{ strtoupper($material->tipe) }}</p>
                            </div>
                            <a href="{{ route('student.materials.download', $material) }}" class="text-primary hover:text-primary-container">
                                <span class="material-symbols-outlined">download</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @endif

            @forelse($transactions as $transaction)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-5">
                <h2 class="font-headline-card text-headline-card text-text-main mb-3">{{ $transaction->mentor->user->name }} - {{ $transaction->schedule?->waktu_mulai?->format('d M Y') ?? 'Sesi' }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($transaction->materials as $material)
                    <div class="bg-surface rounded-lg border border-outline-variant p-3 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-3xl">description</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-label-bold text-label-bold text-text-main truncate">{{ $material->judul }}</p>
                            <p class="font-label-sm text-label-sm text-text-muted">{{ strtoupper($material->tipe) }}</p>
                        </div>
                        <a href="{{ route('student.materials.download', $material) }}" class="text-primary hover:text-primary-container">
                            <span class="material-symbols-outlined">download</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            @if($groupedGeneral->isEmpty())
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-5xl text-text-muted mb-3">folder_off</span>
                <p class="font-body-main text-body-main text-text-muted">Belum ada materi tersedia.</p>
            </div>
            @endif
            @endforelse
        </div>
    </main>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Materi Saya')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
    <aside class="fixed left-0 h-full w-sidebar-width bg-surface-container border-r border-outline-variant flex flex-col p-4 z-20">
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
        </nav>
        <div class="border-t border-outline-variant pt-4 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors w-full text-left"><span class="material-symbols-outlined">logout</span>Keluar</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-sidebar-width h-full overflow-y-auto">
        <div class="p-margin-desktop max-w-container-max mx-auto space-y-6">
            <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Materi Pembelajaran</h1>

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
                        <a href="{{ asset('storage/' . str_replace('public/', '', $material->file_path)) }}" class="text-primary hover:text-primary-container" download>
                            <span class="material-symbols-outlined">download</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-5xl text-text-muted mb-3">folder_off</span>
                <p class="font-body-main text-body-main text-text-muted">Belum ada materi tersedia.</p>
            </div>
            @endforelse
        </div>
    </main>
</div>
@endsection

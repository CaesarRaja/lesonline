@extends('layouts.app')

@section('title', 'Upload Materi')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
    <aside class="hidden md:flex bg-surface-container flex-col h-full p-4 fixed left-0 w-sidebar-width border-r border-outline-variant z-20">
        <div class="mb-8 px-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined" style="font-size: 20px;">menu_book</span>
            </div>
            <div>
                <h1 class="font-display-logo text-display-logo text-primary">BimbelEdu</h1>
                <p class="font-label-sm text-label-sm text-on-surface-variant">Mentor Portal</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined fill-icon">cloud_upload</span> Upload Materi
            </a>
        </nav>
        <div class="pt-4 border-t border-outline-variant mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-sm text-label-sm transition-colors w-full text-left">
                    <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto">
        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-gutter">
            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl">{{ session('success') }}</div>
            @endif

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                <h2 class="font-headline-card text-headline-card text-on-surface mb-4">Upload Materi Baru</h2>
                <form method="POST" action="{{ route('mentor.materials.upload') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Judul Materi</label>
                        <input name="judul" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">File (PDF, DOC, PPT, ZIP - max 10MB)</label>
                        <input type="file" name="file" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                    </div>
                    <div>
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Khusus Transaksi (opsional)</label>
                        <select name="transaction_id" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main">
                            <option value="">Semua siswa</option>
                            @foreach($transactions as $t)
                            <option value="{{ $t->id }}">{{ $t->student->name }} - {{ $t->schedule?->waktu_mulai?->format('d M') ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="bg-primary text-on-primary font-label-bold text-label-bold px-6 py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm" type="submit">Upload</button>
                </form>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                <h2 class="font-headline-card text-headline-card text-on-surface mb-4">Daftar Materi</h2>
                @forelse($materials as $material)
                <div class="flex items-center justify-between py-3 border-b border-outline-variant/30 last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">description</span>
                        <div>
                            <p class="font-label-bold text-label-bold text-text-main">{{ $material->judul }}</p>
                            <p class="font-label-sm text-label-sm text-text-muted">{{ strtoupper($material->tipe) }} - {{ $material->transaction ? $material->transaction->student->name : 'Semua siswa' }}</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . str_replace('public/', '', $material->file_path)) }}" class="text-primary hover:text-primary-container" download target="_blank">
                        <span class="material-symbols-outlined">download</span>
                    </a>
                </div>
                @empty
                <p class="font-body-main text-body-main text-text-muted text-center py-4">Belum ada materi.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Broadcast')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Broadcast Pengumuman</h2>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.broadcasts.send') }}" class="space-y-4">
            @csrf
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Judul</label>
                <input name="judul" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
            </div>
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Isi Pengumuman</label>
                <textarea name="isi" rows="4" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required></textarea>
            </div>
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Target</label>
                <select name="target_role" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main">
                    <option value="all">Semua User</option>
                    <option value="student">Student</option>
                    <option value="mentor">Mentor</option>
                </select>
            </div>
            <button class="bg-primary text-on-primary font-label-bold text-label-bold px-6 py-2.5 rounded-lg hover:bg-primary/90 transition-colors" type="submit">Kirim Pengumuman</button>
        </form>
    </div>
    @if(isset($broadcasts) && $broadcasts->isNotEmpty())
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-outline-variant bg-bg-base"><h3 class="font-headline-card text-headline-card text-on-surface">Riwayat Broadcast</h3></div>
        <div class="divide-y divide-outline-variant">
            @foreach($broadcasts as $b)
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="font-label-bold text-label-bold text-on-surface">{{ $b->judul }}</h4>
                    <span class="font-label-sm text-label-sm text-text-muted">{{ $b->target_role }} - {{ $b->created_at->format('d M Y H:i') }}</span>
                </div>
                <p class="font-body-main text-body-main text-text-muted">{{ $b->isi }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</main>
</div>
@endsection

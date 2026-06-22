@extends('layouts.app')
@section('title', 'Moderasi Ulasan')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">
@include('admin._sidebar')
<main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Moderasi Ulasan</h2>
    </div>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead><tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Student</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Mentor</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Rating</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Komentar</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($reviews as $r)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-body-main text-on-surface">{{ $r->transaction->student->name }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $r->transaction->mentor->user->name }}</td>
                    <td class="px-6 py-4">
                        <span class="flex items-center gap-1 text-secondary-container">
                            @for($i = 0; $i < 5; $i++)
                            <span class="material-symbols-outlined text-[16px] {{ $i < $r->rating ? 'fill-icon' : '' }}">star</span>
                            @endfor
                        </span>
                    </td>
                    <td class="px-6 py-4 font-body-main text-text-muted max-w-xs truncate">{{ $r->komentar ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-error-container text-on-error-container px-3 py-1 rounded-lg text-[12px] font-semibold hover:bg-error hover:text-white transition-colors">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center font-body-main text-body-main text-text-muted">Belum ada ulasan.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</main>
</div>
@endsection

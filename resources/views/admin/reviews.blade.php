@extends('layouts.app')
@section('title', 'Moderasi Ulasan')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Moderasi Ulasan</h2>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
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
</main>
</div>
@endsection

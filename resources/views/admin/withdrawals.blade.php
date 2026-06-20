@extends('layouts.app')
@section('title', 'Penarikan Dana')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Penarikan Dana Mentor</h2>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead><tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Mentor</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Jumlah</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Bank</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">No. Rek</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Status</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($withdrawals as $w)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-body-main text-on-surface font-medium">{{ $w->mentor->user->name }}</td>
                    <td class="px-6 py-4 font-body-main text-on-surface">Rp {{ number_format($w->jumlah, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $w->bank }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $w->no_rekening }} a/n {{ $w->atas_nama }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold
                            @if($w->status === 'approved') bg-success-bg text-success-text
                            @elseif($w->status === 'rejected') bg-error-container text-on-error-container
                            @else bg-pending-bg text-pending-text @endif">{{ $w->status }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($w->status === 'pending')
                        <div class="flex gap-1">
                            <form method="POST" action="{{ route('admin.withdrawals.resolve', $w) }}">
                                @csrf <input type="hidden" name="status" value="approved">
                                <button class="bg-success-bg text-success-text px-3 py-1 rounded-lg text-[12px] font-semibold hover:bg-success-text hover:text-white">Setuju</button>
                            </form>
                            <form method="POST" action="{{ route('admin.withdrawals.resolve', $w) }}" class="flex gap-1">
                                @csrf <input type="hidden" name="status" value="rejected">
                                <input name="alasan_penolakan" placeholder="Alasan" class="border border-outline-variant rounded px-2 py-1 text-xs w-24">
                                <button class="bg-error-container text-on-error-container px-3 py-1 rounded-lg text-[12px] font-semibold hover:bg-error hover:text-white">Tolak</button>
                            </form>
                        </div>
                        @elseif($w->status === 'rejected' && $w->alasan_penolakan)
                        <p class="font-label-sm text-label-sm text-text-muted mt-1">{{ $w->alasan_penolakan }}</p>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center font-body-main text-body-main text-text-muted">Belum ada pengajuan penarikan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
</div>
@endsection

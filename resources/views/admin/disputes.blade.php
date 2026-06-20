@extends('layouts.app')
@section('title', 'Sengketa')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Pusat Resolusi Sengketa</h2>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead><tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Student</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Mentor</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Alasan</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Status</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($disputes as $d)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-body-main text-on-surface">{{ $d->student->name }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $d->transaction->mentor->user->name }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted max-w-xs truncate">{{ $d->alasan }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold
                            @if($d->status === 'resolved') bg-success-bg text-success-text
                            @elseif($d->status === 'rejected') bg-surface-container-high text-text-muted
                            @else bg-pending-bg text-pending-text @endif">{{ $d->status }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($d->status === 'open')
                        <form method="POST" action="{{ route('admin.disputes.resolve', $d) }}" class="space-y-1">
                            @csrf
                            <select name="status" class="border border-outline-variant rounded px-2 py-1 text-xs w-full">
                                <option value="resolved">Resolved (Refund)</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <textarea name="catatan_resolusi" placeholder="Catatan resolusi..." class="border border-outline-variant rounded px-2 py-1 text-xs w-full" rows="2"></textarea>
                            <button class="bg-primary text-on-primary px-3 py-1 rounded-lg text-[12px] font-semibold hover:bg-primary/90 w-full">Proses</button>
                        </form>
                        @else
                        <p class="font-label-sm text-label-sm text-text-muted">{{ $d->catatan_resolusi }}</p>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center font-body-main text-body-main text-text-muted">Tidak ada sengketa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
</div>
@endsection

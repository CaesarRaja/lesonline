@extends('layouts.app')
@section('title', 'Verifikasi Mentor')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">
@include('admin._sidebar')
<main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Verifikasi Profil Mentor (KYC)</h2>
    </div>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead><tr class="bg-surface-container-low border-b border-outline-variant">
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Nama</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Email</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Keahlian</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Status</th>
                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-outline-variant">
                @foreach($mentors as $user)
                <tr class="hover:bg-surface-container-lowest transition-colors">
                    <td class="px-6 py-4 font-body-main text-on-surface font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $user->email }}</td>
                    <td class="px-6 py-4 font-body-main text-text-muted">{{ $user->mentor?->keahlian ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold
                            @if($user->verification_status === 'verified') bg-success-bg text-success-text
                            @elseif($user->verification_status === 'rejected') bg-error-container text-on-error-container
                            @else bg-pending-bg text-pending-text @endif">
                            {{ $user->verification_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-y-1">
                        <form method="POST" action="{{ route('admin.verify', $user) }}">
                            @csrf <input type="hidden" name="status" value="verified">
                            <button class="bg-success-bg text-success-text px-3 py-1 rounded-lg text-[12px] font-semibold hover:bg-success-text hover:text-white transition-colors w-full">Verifikasi</button>
                        </form>
                        <form method="POST" action="{{ route('admin.verify', $user) }}" class="flex gap-1">
                            @csrf <input type="hidden" name="status" value="rejected">
                            <input name="alasan" placeholder="Alasan" class="border border-outline-variant rounded px-2 py-1 text-xs w-full">
                            <button class="bg-error-container text-on-error-container px-3 py-1 rounded-lg text-[12px] font-semibold hover:bg-error hover:text-white transition-colors whitespace-nowrap">Tolak</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</main>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Komisi Platform')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Konfigurasi Komisi Platform</h2>
    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.fees.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Persentase Komisi (%)</label>
                <input name="persentase" type="number" step="0.01" min="0" max="100" value="{{ $fee->persentase ?? 10 }}" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
            </div>
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Nominal Tetap (Rp)</label>
                <input name="nominal_tetap" type="number" min="0" value="{{ $fee->nominal_tetap ?? 0 }}" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
            </div>
            <button class="bg-primary text-on-primary font-label-bold text-label-bold px-6 py-2.5 rounded-lg hover:bg-primary/90 transition-colors" type="submit">Simpan Konfigurasi</button>
        </form>
        @if($fee)
        <div class="mt-6 p-4 bg-bg-base rounded-lg">
            <p class="font-label-sm text-label-sm text-text-muted">Saat ini: <span class="font-label-bold text-label-bold text-text-main">{{ $fee->persentase }}% + Rp {{ number_format($fee->nominal_tetap, 0, ',', '.') }}</span></p>
        </div>
        @endif
    </div>
</main>
</div>
@endsection

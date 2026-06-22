@extends('layouts.app')

@section('title', 'Penarikan Saldo')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">
    @include('mentor._sidebar', ['active' => 'withdrawals'])

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto">
        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-gutter">
            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
            @endif

            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Penarikan Saldo</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5">
                    <p class="font-label-sm text-label-sm text-text-muted">Total Pendapatan</p>
                    <p class="font-price-display text-price-display text-on-surface">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5">
                    <p class="font-label-sm text-label-sm text-text-muted">Sudah Ditarik</p>
                    <p class="font-price-display text-price-display text-on-surface">Rp {{ number_format($totalEarnings - $saldo, 0, ',', '.') }}</p>
                </div>
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5">
                    <p class="font-label-sm text-label-sm text-text-muted">Saldo Tersedia</p>
                    <p class="font-price-display text-price-display text-primary">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-gutter">
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                    <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Ajukan Penarikan</h3>
                    <form method="POST" action="{{ route('mentor.withdrawal') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="font-label-sm text-label-sm text-text-muted block mb-1">Jumlah Penarikan</label>
                            <input name="jumlah" type="number" min="50000" placeholder="Min. Rp 50.000" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Nama Bank</label>
                                <input name="bank" placeholder="Contoh: BCA" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                            </div>
                            <div>
                                <label class="font-label-sm text-label-sm text-text-muted block mb-1">No. Rekening</label>
                                <input name="no_rekening" placeholder="Nomor rekening" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                            </div>
                        </div>
                        <div>
                            <label class="font-label-sm text-label-sm text-text-muted block mb-1">Atas Nama</label>
                            <input name="atas_nama" placeholder="Nama pemilik rekening" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                        </div>
                        <button class="w-full bg-primary text-on-primary font-label-bold text-label-bold py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm" type="submit">Ajukan Penarikan</button>
                    </form>
                </div>

                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                    <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Riwayat Penarikan</h3>
                    @forelse($withdrawals as $w)
                    <div class="flex items-center justify-between py-3 border-b border-outline-variant/30 last:border-0">
                        <div>
                            <p class="font-label-bold text-label-bold text-text-main">Rp {{ number_format($w->jumlah, 0, ',', '.') }}</p>
                            <p class="font-label-sm text-label-sm text-text-muted">{{ $w->bank }} - {{ $w->no_rekening }} ({{ $w->atas_nama }})</p>
                            <p class="font-label-sm text-label-sm text-text-muted">{{ $w->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="font-label-sm text-label-sm px-3 py-1 rounded-full
                            @if($w->status === 'approved') bg-success-bg text-success-text
                            @elseif($w->status === 'rejected') bg-error-container text-on-error-container
                            @else bg-pending-bg text-pending-text @endif">
                            {{ $w->status }}
                        </span>
                    </div>
                    @empty
                    <p class="font-body-main text-body-main text-text-muted text-center py-8">Belum ada penarikan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

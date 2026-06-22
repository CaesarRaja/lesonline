@extends('layouts.app')

@section('title', 'Penarikan Saldo')

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
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.schedules') }}">
                <span class="material-symbols-outlined">calendar_month</span> Jadwal
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.bundles') }}">
                <span class="material-symbols-outlined">inventory_2</span> Paket Belajar
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.materials') }}">
                <span class="material-symbols-outlined">cloud_upload</span> Upload Materi
            </a>
            <a class="flex items-center gap-3 px-4 py-3 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined">account_balance</span> Penarikan Saldo
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.export-pdf') }}">
                <span class="material-symbols-outlined">picture_as_pdf</span> Export Laporan
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined">settings</span> Pengaturan
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
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
            @endif

            <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Penarikan Saldo</h1>

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

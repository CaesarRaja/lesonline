@extends('layouts.app')

@section('title', 'Paket Belajar')

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
            <a class="flex items-center gap-3 px-4 py-3 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined">inventory_2</span> Paket Belajar
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.materials') }}">
                <span class="material-symbols-outlined">cloud_upload</span> Upload Materi
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentor.withdrawals') }}">
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

            <div class="flex items-center justify-between">
                <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Paket Belajar</h1>
                <button onclick="openModal()" class="bg-primary text-on-primary font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Buat Paket
                </button>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                @forelse($mentor->bundles as $bundle)
                <div class="flex items-center justify-between py-3 border-b border-outline-variant/30 last:border-0">
                    <div>
                        <p class="font-label-bold text-label-bold text-text-main">{{ $bundle->nama }}</p>
                        <p class="font-label-sm text-label-sm text-text-muted">{{ $bundle->jumlah_sesi }} sesi - Rp {{ number_format($bundle->harga, 0, ',', '.') }}</p>
                        @if($bundle->deskripsi)
                        <p class="font-label-sm text-label-sm text-text-muted">{{ $bundle->deskripsi }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('mentor.bundles.destroy', $bundle) }}" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                        @csrf @method('DELETE')
                        <button class="text-error hover:text-error/80"><span class="material-symbols-outlined">delete</span></button>
                    </form>
                </div>
                @empty
                <p class="font-body-main text-body-main text-text-muted text-center py-8">Belum ada paket belajar.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>

<style>
@keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes modalFadeOut { from { opacity: 1; } to { opacity: 0; } }
@keyframes modalSlideUp { from { opacity: 0; transform: translateY(24px) scale(0.96); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes modalSlideDown { from { opacity: 1; transform: translateY(0) scale(1); } to { opacity: 0; transform: translateY(24px) scale(0.96); } }
.modal-overlay-show { animation: modalFadeIn 0.2s ease-out forwards; }
.modal-overlay-hide { animation: modalFadeOut 0.15s ease-in forwards; }
.modal-content-show { animation: modalSlideUp 0.25s ease-out forwards; }
.modal-content-hide { animation: modalSlideDown 0.15s ease-in forwards; }
</style>

<div id="bundle-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div id="modal-card" class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl p-6 w-full max-w-lg mx-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-headline-card text-headline-card text-on-surface">Buat Paket Belajar Baru</h2>
            <button onclick="closeModal()" class="text-on-surface-variant hover:text-error"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('mentor.bundles.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Nama Paket</label>
                <input name="nama" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
            </div>
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Deskripsi (opsional)</label>
                <input name="deskripsi" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Jumlah Sesi</label>
                    <input name="jumlah_sesi" type="number" min="1" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-text-muted block mb-1">Harga Paket</label>
                    <input name="harga" type="number" min="0" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal()" class="bg-surface-variant text-on-surface-variant font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-outline-variant transition-colors">Batal</button>
                <button class="bg-primary text-on-primary font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
const overlay = document.getElementById('bundle-modal');
const card = document.getElementById('modal-card');

function openModal() {
    overlay.classList.remove('hidden', 'modal-overlay-hide');
    card.classList.remove('modal-content-hide');
    overlay.classList.add('flex', 'modal-overlay-show');
    card.classList.add('modal-content-show');
}

function closeModal() {
    overlay.classList.remove('modal-overlay-show');
    card.classList.remove('modal-content-show');
    overlay.classList.add('modal-overlay-hide');
    card.classList.add('modal-content-hide');
    card.addEventListener('animationend', function onEnd() {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex', 'modal-overlay-hide');
        card.classList.remove('modal-content-hide');
        card.removeEventListener('animationend', onEnd);
    });
}

overlay.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection

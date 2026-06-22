@extends('layouts.app')

@section('title', 'Upload Materi')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">
    @include('mentor._sidebar', ['active' => 'materials'])

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto">
        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-gutter">
            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl">{{ session('success') }}</div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Daftar Materi</h1>
                </div>
                <button onclick="openModal()" class="bg-primary text-on-primary font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Upload Materi
                </button>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6">
                @forelse($materials as $material)
                <div class="py-3 border-b border-outline-variant/30 last:border-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">description</span>
                            <div>
                                <p class="font-label-bold text-label-bold text-text-main">{{ $material->judul }}</p>
                                <p class="font-label-sm text-label-sm text-text-muted">{{ strtoupper($material->tipe) }} - {{ $material->transaction ? $material->transaction->student->name : 'Semua siswa' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('mentor.materials.download', $material) }}" class="text-primary hover:text-primary-container">
                                <span class="material-symbols-outlined">download</span>
                            </a>
                            <button onclick="toggleEdit({{ $material->id }})" class="text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <form method="POST" action="{{ route('mentor.materials.destroy', $material) }}" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                @csrf @method('DELETE')
                                <button class="text-error hover:text-error/80"><span class="material-symbols-outlined">delete</span></button>
                            </form>
                        </div>
                    </div>
                    <div id="edit-form-{{ $material->id }}" class="hidden mt-3 pt-3 border-t border-outline-variant/20">
                        <form method="POST" action="{{ route('mentor.materials.update', $material) }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @csrf @method('PUT')
                            <input name="judul" value="{{ $material->judul }}" class="border border-outline-variant rounded-lg px-3 py-2 font-body-main text-sm" required>
                            <input type="file" name="file" class="border border-outline-variant rounded-lg px-3 py-2 font-body-main text-sm">
                            <div class="flex gap-2">
                                <button class="bg-primary text-on-primary font-label-bold text-label-bold px-4 py-2 rounded-lg hover:bg-primary/90 transition-colors text-sm" type="submit">Simpan</button>
                                <button type="button" onclick="toggleEdit({{ $material->id }})" class="bg-surface-variant text-on-surface-variant font-label-bold text-label-bold px-4 py-2 rounded-lg hover:bg-outline-variant transition-colors text-sm">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <p class="font-body-main text-body-main text-text-muted text-center py-4">Belum ada materi.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes modalFadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
@keyframes modalSlideUp {
    from { opacity: 0; transform: translateY(24px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes modalSlideDown {
    from { opacity: 1; transform: translateY(0) scale(1); }
    to { opacity: 0; transform: translateY(24px) scale(0.96); }
}
.modal-overlay-show { animation: modalFadeIn 0.2s ease-out forwards; }
.modal-overlay-hide { animation: modalFadeOut 0.15s ease-in forwards; }
.modal-content-show { animation: modalSlideUp 0.25s ease-out forwards; }
.modal-content-hide { animation: modalSlideDown 0.15s ease-in forwards; }
</style>

<!-- Modal Upload -->
<div id="upload-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div id="modal-card" class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-xl p-6 w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-headline-card text-headline-card text-on-surface">Upload Materi Baru</h2>
            <button onclick="closeModal()" class="text-on-surface-variant hover:text-error">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('mentor.materials.upload') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Judul Materi</label>
                <input name="judul" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
            </div>
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">File (PDF, DOC, PPT, ZIP - max 10MB)</label>
                <input type="file" name="file" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
            </div>
            <div>
                <label class="font-label-sm text-label-sm text-text-muted block mb-1">Khusus Transaksi (opsional)</label>
                <select name="transaction_id" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main">
                    <option value="">Semua siswa</option>
                    @foreach($transactions as $t)
                    <option value="{{ $t->id }}">{{ $t->student->name }} - {{ $t->schedule?->waktu_mulai?->format('d M') ?? '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal()" class="bg-surface-variant text-on-surface-variant font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-outline-variant transition-colors">Batal</button>
                <button class="bg-primary text-on-primary font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm" type="submit">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
const overlay = document.getElementById('upload-modal');
const card = document.getElementById('modal-card');

function toggleEdit(id) {
    const form = document.getElementById('edit-form-' + id);
    form.classList.toggle('hidden');
}

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

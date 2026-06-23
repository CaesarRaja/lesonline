@extends('layouts.app')

@section('title', 'Detail Mentor')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">
    @include('mentor._sidebar', ['active' => 'settings'])

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto">
        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto space-y-gutter">

            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
            @endif

            @if($errors->any())
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1 class="font-display-logo text-2xl text-text-main font-extrabold">Detail Mentor</h1>
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-6 max-w-xl">
                <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Informasi Profil Mentor</h3>

                <form method="POST" action="{{ route('mentor.settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Tarif per Jam (Rp)</label>
                        <input type="number" name="tarif_per_jam" value="{{ old('tarif_per_jam', $mentor->tarif_per_jam) }}" step="0.01" min="0" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main" required>
                    </div>

                    <div class="mb-4">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Keahlian</label>
                        <input type="text" name="keahlian" value="{{ old('keahlian', $mentor->keahlian) }}" placeholder="Contoh: Matematika, Fisika, Bahasa Inggris" class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main">
                    </div>

                    <div class="mb-4">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Bio</label>
                        <textarea name="bio" rows="4" placeholder="Ceritakan tentang diri Anda..." class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main resize-y min-h-[100px]">{{ old('bio', $mentor->bio) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="font-label-sm text-label-sm text-text-muted block mb-1">Link Meeting</label>
                        <input type="url" name="link_meeting" value="{{ old('link_meeting', $mentor->link_meeting) }}" placeholder="https://meet.google.com/..." class="w-full border border-outline-variant rounded-lg px-4 py-2.5 font-body-main">
                    </div>

                    <div class="flex gap-3 justify-end pt-2">
                        <button class="bg-primary text-on-primary font-label-bold text-label-bold px-5 py-2.5 rounded-lg hover:bg-primary/90 transition-colors shadow-sm" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

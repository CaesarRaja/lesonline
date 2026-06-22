@extends('layouts.app')

@section('title', 'Dashboard Student')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
    <aside class="fixed left-0 h-full w-sidebar-width bg-surface-container border-r border-outline-variant flex flex-col p-4 z-20">
        <div class="mb-8 px-2 flex flex-col gap-1">
            <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Student Portal</span>
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.materials') }}">
                <span class="material-symbols-outlined">folder</span>
                Materi Saya
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.payments') }}">
                <span class="material-symbols-outlined">receipt_long</span>
                Pembayaran
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentors.index') }}">
                <span class="material-symbols-outlined">search</span>
                Cari Mentor
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="#">
                <span class="material-symbols-outlined">favorite</span>
                Favorit ({{ $favorites->count() }})
            </a>
        </nav>
        <div class="mt-auto mb-6 px-2">
            <a href="{{ route('mentors.index') }}" class="w-full py-2 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                Pesan Sesi Baru
            </a>
        </div>
        <div class="pt-4 border-t border-outline-variant flex flex-col-reverse" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = ! open" class="flex items-center gap-3 px-2 py-2 w-full rounded-lg hover:bg-surface-variant transition-colors text-left">
                <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-label-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="font-label-bold text-label-bold text-text-main truncate flex-1">{{ auth()->user()->name }}</span>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="mb-2 bg-surface-container-lowest rounded-lg border border-outline-variant shadow-xl overflow-hidden" style="display: none;" @click="open = false">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:bg-surface-variant font-label-bold text-label-bold transition-colors w-full text-left">
                        <span class="material-symbols-outlined">logout</span> Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex-1 ml-sidebar-width h-full overflow-y-auto flex flex-col">
        <header class="h-16 border-b border-outline-variant bg-surface-container-lowest shadow-sm flex items-center justify-between px-margin-desktop sticky top-0 z-10">
            <div>
                <h1 class="font-headline-card text-headline-card text-text-main">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="font-label-sm text-label-sm text-text-muted">Siap untuk belajar hari ini?</p>
            </div>
        </header>

        <div class="p-margin-desktop max-w-container-max mx-auto w-full flex-1 space-y-gutter">
            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
            @endif

            <section class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary-container">
                        <span class="material-symbols-outlined" style="font-size: 24px;">schedule</span>
                    </div>
                    <div>
                        <h2 class="font-label-sm text-label-sm text-text-muted uppercase tracking-wide">Total Jam Belajar</h2>
                        <p class="font-price-display text-price-display text-text-main mt-1">{{ $totalJam }} Jam</p>
                    </div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-lg bg-secondary-fixed flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined" style="font-size: 24px;">event</span>
                    </div>
                    <div>
                        <h2 class="font-label-sm text-label-sm text-text-muted uppercase tracking-wide">Kelas Mendatang</h2>
                        <p class="font-price-display text-price-display text-text-main mt-1">{{ $kelasMendatang->count() }} Sesi</p>
                    </div>
                </div>
            </section>

            @if($kelasMendatang->isNotEmpty())
            <section>
                <h2 class="font-headline-card text-headline-card text-text-main mb-4">Kelas Terdekat</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($kelasMendatang->take(3) as $kelas)
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-5">
                        <span class="inline-block px-2.5 py-1 mb-3 bg-secondary-fixed text-on-secondary-fixed text-label-sm font-label-sm rounded-full">
                            {{ $kelas->schedule->waktu_mulai->format('l, d M H:i') }}
                        </span>
                        <h3 class="font-label-bold text-label-bold text-text-main">{{ $kelas->mentor->user->name }}</h3>
                        <p class="font-label-sm text-label-sm text-text-muted mb-4">{{ $kelas->mentor->keahlian }}</p>
                        <a href="{{ $kelas->mentor->link_meeting }}" target="_blank" class="w-full bg-primary-container text-on-primary-container hover:bg-primary-fixed hover:text-on-primary-fixed rounded-lg font-label-bold text-label-bold py-2.5 flex items-center justify-center gap-2 transition-all">
                            Masuk Kelas Virtual
                            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_forward</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            @if($favorites->isNotEmpty())
            <section>
                <h2 class="font-headline-card text-headline-card text-text-main mb-4">Mentor Favorit</h2>
                <div class="flex gap-4 overflow-x-auto pb-2">
                    @foreach($favorites as $fav)
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-4 flex-shrink-0 w-48">
                        <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-headline-card mx-auto mb-2">
                            {{ strtoupper(substr($fav->mentor->user->name, 0, 2)) }}
                        </div>
                        <p class="font-label-bold text-label-bold text-text-main text-center truncate">{{ $fav->mentor->user->name }}</p>
                        <p class="font-label-sm text-label-sm text-text-muted text-center">Rp {{ number_format($fav->mentor->tarif_per_jam, 0, ',', '.') }}/jam</p>
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('mentors.detail', $fav->mentor) }}" class="flex-1 bg-primary text-on-primary text-center text-label-sm font-label-sm py-1.5 rounded-lg">Pesan</a>
                            <form method="POST" action="{{ route('student.favorite.toggle', $fav->mentor) }}">
                                @csrf
                                <button class="p-1.5 text-error hover:bg-error-container rounded-lg"><span class="material-symbols-outlined text-[18px] fill-icon">favorite</span></button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <section>
                <h2 class="font-headline-card text-headline-card text-text-main mb-4">Riwayat Transaksi</h2>
                <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                    @forelse($transactions as $transaction)
                    <div class="p-4 border-b border-outline-variant last:border-b-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-label-bold text-label-bold text-text-main">{{ $transaction->mentor->user->name }}</p>
                                    @if($transaction->coupon)
                                    <span class="text-label-sm text-success-text bg-success-bg px-2 py-0.5 rounded-full">Diskon</span>
                                    @endif
                                </div>
                                <p class="font-label-sm text-label-sm text-text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                                <p class="font-label-sm text-label-sm text-text-main mt-1">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</p>
                                @if($transaction->jumlah_dibayar && $transaction->jumlah_dibayar < $transaction->total_harga)
                                <p class="font-label-sm text-label-sm text-success-text">Setelah diskon: Rp {{ number_format($transaction->jumlah_dibayar, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                @if($transaction->status_pembayaran === 'success')
                                <span class="px-3 py-1 bg-success-bg text-success-text rounded-full font-label-sm text-label-sm">Lunas</span>
                                @elseif($transaction->status_pembayaran === 'pending')
                                <span class="px-3 py-1 bg-pending-bg text-pending-text rounded-full font-label-sm text-label-sm">Pending</span>
                                @elseif($transaction->refund_status === 'refunded')
                                <span class="px-3 py-1 bg-error-container text-on-error-container rounded-full font-label-sm text-label-sm">Refund</span>
                                @else
                                <span class="px-3 py-1 bg-surface-container-high text-text-muted rounded-full font-label-sm text-label-sm">{{ $transaction->status_pembayaran }}</span>
                                @endif
                                @if($transaction->status_pembayaran === 'success' && $transaction->schedule && $transaction->schedule->waktu_mulai->diffInHours(now()) >= 24 && !$transaction->cancelled_at)
                                <div class="mt-2 space-y-1">
                                    <form method="POST" action="{{ route('student.cancel', $transaction) }}" class="flex gap-1">
                                        @csrf
                                        <input name="alasan" placeholder="Alasan pembatalan" class="border border-outline-variant rounded px-2 py-1 text-xs flex-1" required>
                                        <button class="text-xs text-error hover:underline whitespace-nowrap" type="submit">Batal</button>
                                    </form>
                                    @if($transaction->mentor->schedules()->where('status', 'available')->where('id', '!=', $transaction->schedule_id)->exists())
                                    <form method="POST" action="{{ route('student.reschedule', $transaction) }}" class="flex gap-1">
                                        @csrf @method('PUT')
                                        <select name="new_schedule_id" class="border border-outline-variant rounded px-2 py-1 text-xs flex-1" required>
                                            <option value="">Pilih jadwal baru...</option>
                                            @foreach($transaction->mentor->schedules()->where('status', 'available')->where('id', '!=', $transaction->schedule_id)->get() as $sched)
                                            <option value="{{ $sched->id }}">{{ $sched->waktu_mulai->format('d M H:i') }} - {{ $sched->waktu_selesai->format('H:i') }}</option>
                                            @endforeach
                                        </select>
                                        <button class="text-xs text-primary hover:underline whitespace-nowrap" type="submit">Reschedule</button>
                                    </form>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($transaction->status_pembayaran === 'success' && !$transaction->review)
                        <div class="mt-3 pt-3 border-t border-outline-variant/30">
                            <form method="POST" action="{{ route('student.review.store', $transaction) }}" class="flex gap-2 items-end">
                                @csrf
                                <div>
                                    <select name="rating" class="border border-outline-variant rounded-lg px-3 py-1.5 text-label-sm" required>
                                        <option value="5">5 - Sangat Baik</option>
                                        <option value="4">4 - Baik</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="2">2 - Kurang</option>
                                        <option value="1">1 - Buruk</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <input name="komentar" class="w-full border border-outline-variant rounded-lg px-3 py-1.5 text-body-main" placeholder="Tulis ulasan..." maxlength="500">
                                </div>
                                <button class="bg-primary text-on-primary font-label-bold text-label-bold px-4 py-1.5 rounded-lg hover:bg-primary/90 transition-colors whitespace-nowrap" type="submit">Kirim</button>
                            </form>
                        </div>
                        @endif
                        @if($transaction->status_pembayaran === 'pending' && $transaction->midtrans_order_id)
                        <div class="mt-2">
                            <span class="font-label-sm text-label-sm text-text-muted">Order: {{ $transaction->midtrans_order_id }}</span>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <span class="material-symbols-outlined text-4xl text-text-muted mb-2">receipt_long</span>
                        <p class="font-body-main text-body-main text-text-muted">Belum ada transaksi.</p>
                        <a href="{{ route('mentors.index') }}" class="mt-3 inline-block bg-primary text-on-primary font-label-bold text-label-bold px-4 py-2 rounded-lg">Cari Mentor</a>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>
@endsection

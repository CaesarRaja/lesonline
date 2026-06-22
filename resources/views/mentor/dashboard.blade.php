@extends('layouts.app')

@section('title', 'Dashboard Mentor')

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
            <a class="flex items-center gap-3 px-4 py-3 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined fill-icon">dashboard</span> Dashboard
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
        <div class="space-y-1 pt-4 border-t border-outline-variant mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-sm text-label-sm transition-colors w-full text-left">
                    <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto flex flex-col">
        <header class="sticky top-0 bg-surface/80 backdrop-blur-md z-10 border-b border-outline-variant px-margin-mobile md:px-margin-desktop py-4 flex items-center justify-between">
            <div>
                <h2 class="font-headline-card text-headline-card text-on-surface">Halo, {{ auth()->user()->name }}!</h2>
                <p class="font-label-sm text-label-sm text-text-muted hidden sm:block">Siap mengajar hari ini?</p>
            </div>
            <div class="w-10 h-10 rounded-full border-2 border-primary-fixed overflow-hidden bg-primary-fixed flex items-center justify-center text-primary font-label-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </header>

        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto w-full space-y-gutter">
            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container">
                            <span class="material-symbols-outlined">account_balance_wallet</span>
                        </div>
                    </div>
                    <p class="font-label-sm text-label-sm text-text-muted mb-1">Total Pendapatan</p>
                    <h3 class="font-price-display text-price-display text-on-surface">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <p class="font-label-sm text-label-sm text-text-muted mb-1">Siswa Aktif</p>
                    <h3 class="font-price-display text-price-display text-on-surface">{{ $activeStudents }} Siswa</h3>
                </div>
                <div class="bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
                    <p class="font-label-sm text-label-sm text-text-muted mb-1">Total Sesi</p>
                    <h3 class="font-price-display text-price-display text-on-surface">{{ $totalSessions }} Sesi</h3>
                </div>
            </div>


                    <h3 class="font-headline-card text-headline-card text-on-surface">Jadwal Mendatang</h3>
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden divide-y divide-surface-variant">
                        @forelse($upcomingSchedules as $schedule)
                        <div class="p-5 hover:bg-surface-bright transition-colors flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-headline-card">
                                    {{ strtoupper(substr($schedule->transaction->student->name ?? '??', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="font-label-bold text-label-bold text-on-surface">{{ $schedule->transaction->student->name ?? 'Unknown' }}</h4>
                                    <p class="font-body-main text-body-main text-text-muted">{{ $schedule->waktu_mulai->format('l, d M Y H:i') }}</p>
                                </div>
                            </div>
                            <a href="{{ $mentor->link_meeting }}" target="_blank" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-label-bold text-label-bold shadow-sm hover:shadow-md transition-all whitespace-nowrap">Mulai Kelas</a>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <span class="material-symbols-outlined text-4xl text-text-muted mb-2">event_busy</span>
                            <p class="font-body-main text-body-main text-text-muted">Belum ada jadwal mendatang.</p>
                        </div>
                        @endforelse
                    </div>

                    @if($ratingData->isNotEmpty())
                    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5">
                        <h3 class="font-headline-card text-headline-card text-on-surface mb-3">Rating Analytics</h3>
                        @foreach($ratingData as $bulan => $data)
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-label-sm text-label-sm text-text-muted w-20">{{ $bulan }}</span>
                            <div class="flex-1 h-2 bg-surface-variant rounded-full overflow-hidden">
                                <div class="h-full bg-secondary-container rounded-full" style="width: {{ $data->avg('review.rating') * 20 }}%"></div>
                            </div>
                            <span class="font-label-bold text-label-bold text-text-main text-sm">{{ number_format($data->avg('review.rating'), 1) }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm p-5">
                <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Pesanan Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="px-4 py-3 font-label-sm text-label-sm text-text-muted">Siswa</th>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-text-muted">Sesi</th>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-text-muted">Nominal</th>
                                <th class="px-4 py-3 font-label-sm text-label-sm text-text-muted">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr class="border-b border-outline-variant/30">
                                <td class="px-4 py-3 font-label-bold text-label-bold text-text-main">{{ $booking->student->name }}</td>
                                <td class="px-4 py-3 font-body-main text-body-main text-text-muted">{{ $booking->schedule?->waktu_mulai?->format('d M H:i') ?? '-' }}</td>
                                <td class="px-4 py-3 font-label-bold text-label-bold text-text-main">Rp {{ number_format($booking->jumlah_dibayar ?? $booking->total_harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if($booking->status_pembayaran === 'success')
                                    <span class="px-2 py-1 bg-success-bg text-success-text rounded-md font-label-sm text-label-sm">Success</span>
                                    @elseif($booking->status_pembayaran === 'pending')
                                    <span class="px-2 py-1 bg-pending-bg text-pending-text rounded-md font-label-sm text-label-sm">Pending</span>
                                    @else
                                    <span class="px-2 py-1 bg-surface-container-high text-text-muted rounded-md font-label-sm text-label-sm">{{ $booking->status_pembayaran }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center font-body-main text-body-main text-text-muted">Belum ada pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

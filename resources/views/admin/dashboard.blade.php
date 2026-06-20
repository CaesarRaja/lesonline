@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
    <aside class="hidden md:flex flex-col h-full w-sidebar-width bg-surface-container border-r border-outline-variant p-4 flex-shrink-0 z-20">
        <div class="mb-8 px-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined fill-icon">admin_panel_settings</span>
            </div>
            <div>
                <h1 class="font-display-logo text-display-logo text-primary">BimbelEdu</h1>
                <p class="font-label-sm text-label-sm text-on-surface-variant">Admin Portal</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a class="flex items-center gap-3 px-3 py-2 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#"><span class="material-symbols-outlined fill-icon">dashboard</span> Dashboard</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.users') }}"><span class="material-symbols-outlined">group</span> Manajemen User</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.verifications') }}"><span class="material-symbols-outlined">verified_user</span> Verifikasi Mentor</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.fees') }}"><span class="material-symbols-outlined">payments</span> Komisi Platform</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.withdrawals') }}"><span class="material-symbols-outlined">account_balance</span> Penarikan Dana</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.disputes') }}"><span class="material-symbols-outlined">gavel</span> Sengketa</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.reviews') }}"><span class="material-symbols-outlined">rate_review</span> Moderasi Ulasan</a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold" href="{{ route('admin.broadcasts') }}"><span class="material-symbols-outlined">campaign</span> Broadcast</a>
        </nav>
        <div class="mt-auto pt-4 border-t border-outline-variant">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-sm text-label-sm w-full text-left"><span class="material-symbols-outlined text-[18px]">logout</span> Keluar</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-y-auto bg-surface-container-lowest">
        <div class="flex-1 p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto w-full space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display-logo text-[24px] leading-8 font-extrabold text-on-surface">Dashboard Admin</h2>
                    <p class="text-text-muted mt-1 font-body-main text-body-main">Pantau aktivitas platform BimbelEdu.</p>
                </div>
                <a href="{{ route('admin.export-transactions-pdf') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-label-bold text-label-bold rounded-lg hover:bg-primary-fixed transition-all"><span class="material-symbols-outlined">picture_as_pdf</span> Cetak Laporan PDF</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm">
                    <p class="font-label-sm text-label-sm text-text-muted uppercase tracking-wider mb-1">Total User</p>
                    <h3 class="font-price-display text-price-display text-on-surface text-2xl">{{ number_format($totalUsers) }}</h3>
                    <p class="font-label-sm text-label-sm text-text-muted mt-1">{{ $totalStudents }} Student / {{ $totalMentors }} Mentor</p>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm">
                    <p class="font-label-sm text-label-sm text-text-muted uppercase tracking-wider mb-1">Transaksi Sukses</p>
                    <h3 class="font-price-display text-price-display text-on-surface text-2xl">{{ number_format($totalTransactions) }}</h3>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm">
                    <p class="font-label-sm text-label-sm text-text-muted uppercase tracking-wider mb-1">Pendapatan</p>
                    <h3 class="font-price-display text-price-display text-on-surface text-2xl">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm">
                    <p class="font-label-sm text-label-sm text-text-muted uppercase tracking-wider mb-1">Menunggu</p>
                    <h3 class="font-price-display text-price-display text-on-surface text-2xl">{{ $pendingVerifications + $pendingWithdrawals + $openDisputes }}</h3>
                    <p class="font-label-sm text-label-sm text-text-muted mt-1">{{ $pendingVerifications }} verifikasi / {{ $pendingWithdrawals }} penarikan / {{ $openDisputes }} sengketa</p>
                </div>
            </div>

            <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-outline-variant bg-bg-base">
                    <h3 class="font-headline-card text-headline-card text-on-surface">Transaksi Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Student</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Mentor</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Sesi</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Nominal</th>
                                <th class="px-6 py-4 font-label-sm text-label-sm text-text-muted uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant bg-surface">
                            @forelse($recentTransactions as $t)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="px-6 py-4 font-body-main text-body-main text-on-surface font-medium">{{ $t->student->name }}</td>
                                <td class="px-6 py-4 font-body-main text-body-main text-on-surface">{{ $t->mentor->user->name }}</td>
                                <td class="px-6 py-4 font-body-main text-body-main text-text-muted">{{ $t->schedule ? $t->schedule->waktu_mulai->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4 font-body-main text-body-main text-on-surface font-medium">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @if($t->status_pembayaran === 'success')
                                    <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-success-bg text-success-text"><span class="w-1.5 h-1.5 rounded-full bg-success-text inline-block mr-1"></span>Sukses</span>
                                    @elseif($t->status_pembayaran === 'pending')
                                    <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-pending-bg text-pending-text"><span class="w-1.5 h-1.5 rounded-full bg-pending-text animate-pulse inline-block mr-1"></span>Pending</span>
                                    @else
                                    <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-surface-container-high text-text-muted">{{ $t->status_pembayaran }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center font-body-main text-body-main text-text-muted">Belum ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

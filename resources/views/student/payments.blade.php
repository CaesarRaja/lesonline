@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 md:hidden"></div>

    <aside class="fixed left-0 h-full w-sidebar-width bg-surface-container border-r border-outline-variant flex flex-col p-4 z-40 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
        <div class="mb-8 px-2 flex flex-col gap-1">
            <span class="font-display-logo text-display-logo text-primary">BimbelEdu</span>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Student Portal</span>
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('student.materials') }}">
                <span class="material-symbols-outlined">folder</span>
                Materi Saya
            </a>
            <a class="flex items-center gap-3 px-3 py-2 bg-primary-container text-on-primary-container rounded-lg font-label-bold text-label-bold" href="#">
                <span class="material-symbols-outlined">receipt_long</span>
                Pembayaran
            </a>
            <a class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors" href="{{ route('mentors.index') }}">
                <span class="material-symbols-outlined">search</span>
                Cari Mentor
            </a>
        </nav>
        <div class="mt-auto mb-6 px-2">
            <a href="{{ route('mentors.index') }}" class="w-full py-2 bg-primary text-on-primary rounded-lg font-label-bold text-label-bold shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                Pesan Sesi Baru
            </a>
        </div>
        <div class="border-t border-outline-variant pt-4 flex flex-col gap-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-3 py-2 text-on-surface-variant hover:bg-surface-variant rounded-lg font-label-bold text-label-bold transition-colors w-full text-left" type="submit">
                    <span class="material-symbols-outlined">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-0 md:ml-sidebar-width h-full overflow-y-auto flex flex-col">
        <header class="h-16 border-b border-outline-variant bg-surface-container-lowest shadow-sm flex items-center justify-between px-margin-mobile md:px-margin-desktop sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-on-surface-variant hover:text-text-main hover:bg-surface-variant rounded-lg transition-colors">
                    <span class="material-symbols-outlined" x-show="!sidebarOpen">menu</span>
                    <span class="material-symbols-outlined" x-show="sidebarOpen" x-cloak>close</span>
                </button>
                <div>
                    <h1 class="font-headline-card text-headline-card text-text-main">Riwayat Pembayaran</h1>
                    <p class="font-label-sm text-label-sm text-text-muted">Semua transaksi pembayaran kamu</p>
                </div>
            </div>
        </header>

        <div class="p-margin-mobile md:p-margin-desktop max-w-container-max mx-auto w-full flex-1 space-y-4">
            @if(session('success'))
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ session('error') }}</div>
            @endif

            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[640px]">
                    <thead><tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-5 py-3 font-label-sm text-label-sm text-text-muted uppercase">Order ID</th>
                        <th class="px-5 py-3 font-label-sm text-label-sm text-text-muted uppercase">Mentor</th>
                        <th class="px-5 py-3 font-label-sm text-label-sm text-text-muted uppercase">Tanggal</th>
                        <th class="px-5 py-3 font-label-sm text-label-sm text-text-muted uppercase">Total</th>
                        <th class="px-5 py-3 font-label-sm text-label-sm text-text-muted uppercase">Status</th>
                        <th class="px-5 py-3 font-label-sm text-label-sm text-text-muted uppercase">Detail</th>
                    </tr></thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="px-5 py-3 font-body-main text-body-main text-text-muted text-sm">{{ $t->midtrans_order_id ?? '—' }}</td>
                            <td class="px-5 py-3 font-label-bold text-label-bold text-on-surface">{{ $t->mentor->user->name }}</td>
                            <td class="px-5 py-3 font-body-main text-body-main text-text-muted text-sm">{{ $t->created_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3 font-label-bold text-label-bold text-on-surface">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                @if($t->status_pembayaran === 'success')
                                <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-success-bg text-success-text">Lunas</span>
                                @elseif($t->status_pembayaran === 'pending')
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-pending-bg text-pending-text">Pending</span>
                                    <form method="POST" action="{{ route('student.pay', $t) }}" class="inline">
                                        @csrf
                                        <button class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-primary text-on-primary hover:bg-primary/90 transition-colors">Bayar</button>
                                    </form>
                                </div>
                                @elseif($t->refund_status === 'refunded')
                                <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-error-container text-on-error-container">Refund</span>
                                @elseif($t->status_pembayaran === 'failed')
                                <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-surface-container-high text-text-muted">Gagal</span>
                                @else
                                <span class="px-2.5 py-1 rounded-md text-[12px] font-semibold bg-surface-container-high text-text-muted">{{ $t->status_pembayaran }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <button onclick="openDetail({{ $t->id }})" class="text-primary hover:bg-primary-fixed p-1.5 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center font-body-main text-body-main text-text-muted">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            <div class="mt-4">{{ $transactions->links() }}</div>
        </div>
    </main>
</div>

{{-- Modal Detail Transaksi --}}
<div id="detail-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4" onclick="if(event.target===this)closeDetail()">
    <div class="bg-surface rounded-2xl border border-outline-variant shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-headline-card text-headline-card text-on-surface">Detail Transaksi</h3>
            <button onclick="closeDetail()" class="text-text-muted hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div id="detail-content" class="space-y-3 font-body-main text-body-main">
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Order ID</span>
                <span id="d-order-id" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Transaction ID</span>
                <span id="d-trans-id" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Mentor</span>
                <span id="d-mentor" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Tanggal Booking</span>
                <span id="d-date" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Jadwal Sesi</span>
                <span id="d-schedule" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Total Harga</span>
                <span id="d-total" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Kupon Diskon</span>
                <span id="d-coupon" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Jumlah Dibayar</span>
                <span id="d-paid" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Status</span>
                <span id="d-status" class="font-label-bold text-label-bold text-right"></span>
            </div>
            <div class="flex justify-between py-2 border-b border-outline-variant/30">
                <span class="text-text-muted">Refund Status</span>
                <span id="d-refund" class="font-label-bold text-label-bold text-on-surface text-right"></span>
            </div>
            @if(config('app.debug'))
            <div class="pt-2">
                <p class="font-label-sm text-label-sm text-text-muted mb-1">Midtrans Response (debug):</p>
                <pre id="d-response" class="bg-bg-base rounded-lg p-3 text-xs overflow-x-auto max-h-32"></pre>
            </div>
            @endif
        </div>
        <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-outline-variant">
            <button onclick="closeDetail()" class="px-5 py-2 border border-outline-variant rounded-lg font-label-bold text-label-bold text-text-muted hover:bg-surface-variant">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const transactions = @json($transactions->items());

function openDetail(id) {
    const t = transactions.find(x => x.id === id);
    if (!t) return;
    document.getElementById('d-order-id').textContent = t.midtrans_order_id || '—';
    document.getElementById('d-trans-id').textContent = t.midtrans_transaction_id || '—';
    document.getElementById('d-mentor').textContent = t.mentor?.user?.name || '—';
    document.getElementById('d-date').textContent = new Date(t.created_at).toLocaleString('id-ID');
    document.getElementById('d-schedule').textContent = t.schedule ? new Date(t.schedule.waktu_mulai).toLocaleString('id-ID') : '—';
    document.getElementById('d-total').textContent = 'Rp ' + Number(t.total_harga).toLocaleString('id-ID');
    document.getElementById('d-coupon').textContent = t.coupon ? t.coupon.kode + ' (' + (t.coupon.tipe === 'percent' ? t.coupon.nilai + '%' : 'Rp ' + Number(t.coupon.nilai).toLocaleString('id-ID')) + ')' : '—';
    document.getElementById('d-paid').textContent = t.jumlah_dibayar ? 'Rp ' + Number(t.jumlah_dibayar).toLocaleString('id-ID') : '—';

    const statusEl = document.getElementById('d-status');
    if (t.status_pembayaran === 'success') { statusEl.textContent = 'Lunas'; statusEl.className = 'font-label-bold text-label-bold text-right text-success-text'; }
    else if (t.status_pembayaran === 'pending') { statusEl.textContent = 'Pending'; statusEl.className = 'font-label-bold text-label-bold text-right text-pending-text'; }
    else if (t.refund_status === 'refunded') { statusEl.textContent = 'Refund'; statusEl.className = 'font-label-bold text-label-bold text-right text-error'; }
    else { statusEl.textContent = t.status_pembayaran; statusEl.className = 'font-label-bold text-label-bold text-right text-text-muted'; }

    document.getElementById('d-refund').textContent = t.refund_status || '—';

    const respEl = document.getElementById('d-response');
    if (respEl) respEl.textContent = t.midtrans_response ? JSON.stringify(t.midtrans_response, null, 2) : '—';

    document.getElementById('detail-modal').classList.remove('hidden');
}

function closeDetail() {
    document.getElementById('detail-modal').classList.add('hidden');
}
</script>
@endpush
@endsection

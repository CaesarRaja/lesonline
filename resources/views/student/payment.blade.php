@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="min-h-screen bg-bg-base flex items-center justify-center px-4">
    <div class="bg-surface rounded-2xl p-8 border border-outline-variant/30 shadow-sm max-w-lg w-full text-center">
        <div class="w-16 h-16 rounded-full bg-primary-fixed flex items-center justify-center text-primary mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">payment</span>
        </div>
        <h1 class="font-display-logo text-2xl text-text-main font-extrabold mb-2">Pembayaran</h1>
        <p class="font-body-main text-body-main text-text-muted mb-6">Selesaikan pembayaran untuk melanjutkan booking sesi.</p>
        <div class="bg-bg-base rounded-xl p-4 mb-6 text-left">
            <div class="flex justify-between mb-2">
                <span class="font-body-main text-body-main text-text-muted">Order ID</span>
                <span class="font-label-bold text-label-bold text-text-main">{{ $transaction->midtrans_order_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-body-main text-body-main text-text-muted">Total</span>
                <span class="font-price-display text-price-display text-text-main">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        @if(!$snapToken)
        <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20 mb-4">
            Gagal mendapatkan token pembayaran. Silakan coba lagi atau hubungi admin.
        </div>
        <a href="{{ route('student.dashboard') }}" class="block w-full bg-primary text-on-primary font-label-bold text-label-bold py-3 rounded-xl hover:bg-primary/90 transition-all shadow-sm text-center">Kembali ke Dashboard</a>
        @else
        <div id="snap-container"></div>
        <button id="pay-button" class="w-full bg-primary text-on-primary font-label-bold text-label-bold py-3 rounded-xl hover:bg-primary/90 transition-all shadow-sm">
            Bayar Sekarang
        </button>
        <a href="{{ route('student.dashboard') }}" class="block mt-4 font-body-main text-body-main text-primary hover:underline">Kembali ke Dashboard</a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($snapToken)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}" onerror="document.getElementById('pay-button').insertAdjacentHTML('afterend', '<div class=\'mt-3 bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl\'>Gagal memuat Midtrans Snap. Periksa koneksi internet Anda.</div>')"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                window.location.href = '{{ route("student.payment.success") }}?order_id=' + result.order_id + '&transaction_id=' + result.transaction_id;
            },
            onPending: function (result) {
                alert('Menunggu pembayaran...');
                window.location.href = '{{ route("student.dashboard") }}';
            },
            onError: function (result) {
                alert('Pembayaran gagal!');
                window.location.href = '{{ route("student.dashboard") }}';
            }
        });
    });
</script>
@endif
@endpush

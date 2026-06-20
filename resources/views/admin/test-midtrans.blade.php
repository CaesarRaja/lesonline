@extends('layouts.app')
@section('title', 'Test Midtrans')
@section('content')
<div class="flex h-screen overflow-hidden bg-bg-base">
@include('admin._sidebar')
<main class="flex-1 ml-sidebar-width h-full overflow-y-auto bg-surface-container-lowest p-margin-desktop max-w-container-max mx-auto space-y-6">
    <h2 class="font-display-logo text-[24px] font-extrabold text-on-surface">Diagnostik Midtrans</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-surface rounded-xl border border-outline-variant p-5 shadow-sm">
            <p class="font-label-sm text-label-sm text-text-muted mb-1">Server Key</p>
            <p class="font-label-bold text-label-bold text-on-surface">{{ $serverKey ? substr($serverKey, 0, 20) . '...' : 'KOSONG' }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-outline-variant p-5 shadow-sm">
            <p class="font-label-sm text-label-sm text-text-muted mb-1">Client Key</p>
            <p class="font-label-bold text-label-bold text-on-surface">{{ $clientKey ? substr($clientKey, 0, 20) . '...' : 'KOSONG' }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-outline-variant p-5 shadow-sm">
            <p class="font-label-sm text-label-sm text-text-muted mb-1">Mode</p>
            <p class="font-label-bold text-label-bold text-on-surface">{{ $mode }}</p>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant p-6 shadow-sm">
        <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Hasil Test Snap API</h3>
        @if($errors)
            <div class="space-y-2">
                @foreach($errors as $error)
                <div class="bg-error-container text-on-error-container font-label-bold text-label-bold px-4 py-3 rounded-xl border border-error/20">{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if($snapToken)
            <div class="bg-success-bg text-success-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-success-text/20">
                ✅ Snap Token berhasil didapatkan!
            </div>
            <div class="mt-4 bg-bg-base rounded-xl p-4">
                <p class="font-label-sm text-label-sm text-text-muted mb-1">Token:</p>
                <code class="font-body-main text-body-main text-on-surface break-all">{{ $snapToken }}</code>
            </div>
            <div class="mt-4">
                <p class="font-label-sm text-label-sm text-text-muted mb-2">Test Payment Popup:</p>
                <button id="test-pay-btn" class="bg-primary text-on-primary font-label-bold text-label-bold px-6 py-2.5 rounded-lg hover:bg-primary/90">Buka Midtrans Popup</button>
            </div>
            @push('scripts')
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
            <script>
                document.getElementById('test-pay-btn')?.addEventListener('click', function () {
                    snap.pay('{{ $snapToken }}', {
                        onSuccess: function (r) { alert('Sukses!'); },
                        onPending: function () { alert('Pending'); },
                        onError: function (r) { alert('Error: ' + JSON.stringify(r)); },
                    });
                });
            </script>
            @endpush
        @elseif(!$errors)
            <div class="bg-pending-bg text-pending-text font-label-bold text-label-bold px-4 py-3 rounded-xl border border-pending-text/20">Test belum dijalankan.</div>
        @endif
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant p-6 shadow-sm">
        <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Informasi Lingkungan</h3>
        <table class="w-full text-left">
            <tbody class="divide-y divide-outline-variant">
                <tr><td class="py-2 font-label-sm text-label-sm text-text-muted pr-8">PHP Version</td><td class="font-body-main text-body-main text-on-surface">{{ phpversion() }}</td></tr>
                <tr><td class="py-2 font-label-sm text-label-sm text-text-muted pr-8">cURL Enabled</td><td class="font-body-main text-body-main text-on-surface">{{ extension_loaded('curl') ? 'Yes' : 'No' }}</td></tr>
                <tr><td class="py-2 font-label-sm text-label-sm text-text-muted pr-8">Midtrans Package</td><td class="font-body-main text-body-main text-on-surface">{{ class_exists('Midtrans\Snap') ? 'Terinstall' : 'Tidak terinstall' }}</td></tr>
                <tr><td class="py-2 font-label-sm text-label-sm text-text-muted pr-8">APP_ENV</td><td class="font-body-main text-body-main text-on-surface">{{ config('app.env') }}</td></tr>
                <tr><td class="py-2 font-label-sm text-label-sm text-text-muted pr-8">APP_URL</td><td class="font-body-main text-body-main text-on-surface">{{ config('app.url') }}</td></tr>
            </tbody>
        </table>
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant p-6 shadow-sm">
        <h3 class="font-headline-card text-headline-card text-on-surface mb-4">Cara Mendapatkan Key Midtrans</h3>
        <ol class="list-decimal list-inside space-y-2 font-body-main text-body-main text-text-muted">
            <li>Login ke <a href="https://dashboard.sandbox.midtrans.com" class="text-primary hover:underline" target="_blank">Midtrans Dashboard Sandbox</a></li>
            <li>Masuk ke menu <strong>Settings</strong> → <strong>Access Keys</strong></li>
            <li>Salin <strong>Server Key</strong> dan <strong>Client Key</strong></li>
            <li>Isi di file <code>.env</code> project:
                <pre class="bg-bg-base rounded-lg p-3 mt-2 text-sm">MIDTRANS_SERVER_KEY=Mid-server-...anda...
MIDTRANS_CLIENT_KEY=Mid-client-...anda...
MIDTRANS_IS_PRODUCTION=false</pre>
            </li>
            <li>Jalankan <code>php artisan config:clear</code> setelah mengubah .env</li>
            <li>Refresh halaman ini</li>
        </ol>
    </div>
</main>
</div>
@endsection

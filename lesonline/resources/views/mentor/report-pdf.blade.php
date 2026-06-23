<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan Mentor</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #004ac6; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .total { margin-top: 20px; text-align: right; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pendapatan Mentor</h1>
        <p>{{ $mentor->user->name }}</p>
        <p>{{ now()->format('d M Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Sesi</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $t->created_at->format('d M Y') }}</td>
                <td>{{ $t->student->name }}</td>
                <td>{{ $t->schedule ? $t->schedule->waktu_mulai->format('l, H:i') : '-' }}</td>
                <td>Rp {{ number_format($t->jumlah_dibayar ?? $t->total_harga, 0, ',', '.') }}</td>
                <td>{{ $t->status_pembayaran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total">
        Total Pendapatan: Rp {{ number_format($transactions->sum(fn($t) => $t->jumlah_dibayar ?? $t->total_harga), 0, ',', '.') }}
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Laundry</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 12px; color: #222; }
        h2 { margin: 0; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 12px; }
        .header p { margin: 2px 0; }
        .meta { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px 6px; text-align: left; }
        th { background: #f0f0f0; }
        .text-end { text-align: right; }
        .summary { margin-top: 14px; width: 45%; float: right; }
        .summary td { border: none; padding: 3px 6px; }
        .summary .total { font-weight: bold; border-top: 2px solid #333; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ config('app.name') }}</h2>
        <p>Laporan Transaksi Laundry</p>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ $label }}<br>
        <strong>Dicetak:</strong> {{ \Illuminate\Support\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Masuk</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Berat</th>
                <th class="text-end">Total (Rp)</th>
                <th>Status</th>
                <th>Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                    <td>{{ $trx->kode }}</td>
                    <td>{{ $trx->nama_pelanggan }}</td>
                    <td>{{ $trx->layanan->nama ?? '-' }}</td>
                    <td>{{ rtrim(rtrim(number_format($trx->berat, 2), '0'), '.') }} {{ strtoupper($trx->layanan->satuan ?? 'kg') }}</td>
                    <td class="text-end">{{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($trx->status) }}</td>
                    <td>{{ $trx->status_bayar === 'lunas' ? 'Lunas' : 'Belum' }}</td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary">
        <tr><td>Jumlah Transaksi</td><td class="text-end">{{ $ringkasan['jumlah'] }}</td></tr>
        <tr><td>Sudah Lunas</td><td class="text-end">Rp {{ number_format($ringkasan['lunas'], 0, ',', '.') }}</td></tr>
        <tr><td>Belum Bayar</td><td class="text-end">Rp {{ number_format($ringkasan['belum'], 0, ',', '.') }}</td></tr>
        <tr class="total"><td>TOTAL PENDAPATAN</td><td class="text-end">Rp {{ number_format($ringkasan['total'], 0, ',', '.') }}</td></tr>
    </table>
</body>
</html>

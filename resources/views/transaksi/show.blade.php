@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>{{ $transaksi->kode }}</strong>
            <span class="badge text-bg-{{ $transaksi->statusBadge() }}">{{ ucfirst($transaksi->status) }}</span>
        </div>
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr><th width="200">Nama Pelanggan</th><td>{{ $transaksi->nama_pelanggan }}</td></tr>
                <tr><th>No. HP</th><td>{{ $transaksi->no_hp ?? '-' }}</td></tr>
                <tr><th>Alamat</th><td>{{ $transaksi->alamat ?? '-' }}</td></tr>
                @php $satuan = strtoupper($transaksi->layanan->satuan ?? 'kg'); @endphp
                <tr><th>Layanan</th><td>{{ $transaksi->layanan->nama ?? '-' }}</td></tr>
                <tr><th>Jumlah</th><td>{{ rtrim(rtrim(number_format($transaksi->berat, 2), '0'), '.') }} {{ $satuan }}</td></tr>
                <tr><th>Harga / {{ $satuan }}</th><td>Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</td></tr>
                <tr><th>Total</th><td class="fw-bold text-success">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td></tr>
                <tr><th>Pembayaran</th><td>{{ $transaksi->status_bayar === 'lunas' ? 'Lunas' : 'Belum Bayar' }} &middot; {{ ucfirst($transaksi->metode_bayar) }}</td></tr>
                <tr><th>Tanggal Masuk</th><td>{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</td></tr>
                <tr><th>Estimasi Selesai</th><td>{{ $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d/m/Y H:i') : '-' }}</td></tr>
                <tr><th>Catatan</th><td>{{ $transaksi->catatan ?? '-' }}</td></tr>
            </table>
        </div>
        <div class="card-footer bg-white d-flex gap-2">
            <a href="{{ route('transaksi.nota', $transaksi) }}" class="btn btn-success"><i class="bi bi-whatsapp"></i> Nota / Kirim WA</a>
            <a href="{{ route('transaksi.edit', $transaksi) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
@endsection

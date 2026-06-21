@extends('layouts.app')
@section('title', 'Nota Transaksi')

@php
    // Normalisasi nomor HP ke format internasional (62...) untuk link wa.me
    $hp = preg_replace('/[^0-9]/', '', (string) $transaksi->no_hp);
    if (str_starts_with($hp, '0')) {
        $hp = '62' . substr($hp, 1);
    } elseif (str_starts_with($hp, '8')) {
        $hp = '62' . $hp;
    }

    $berat = rtrim(rtrim(number_format($transaksi->berat, 2), '0'), '.');

    // Susun isi pesan nota
    $baris = [
        "*NOTA LAUNDRY - " . config('app.name') . "*",
        "---------------------------------",
        "No. Nota : " . $transaksi->kode,
        "Tanggal  : " . $transaksi->tanggal_masuk->format('d/m/Y'),
        "Nama     : " . $transaksi->nama_pelanggan,
        "---------------------------------",
        "Layanan  : " . ($transaksi->layanan->nama ?? '-'),
        "Berat    : " . $berat . " kg",
        "Harga/kg : Rp " . number_format($transaksi->harga_satuan, 0, ',', '.'),
        "*TOTAL   : Rp " . number_format($transaksi->total_harga, 0, ',', '.') . "*",
        "Bayar    : " . ($transaksi->status_bayar === 'lunas' ? 'LUNAS' : 'BELUM BAYAR'),
        "Status   : " . ucfirst($transaksi->status),
        "Estimasi : " . ($transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d/m/Y H:i') : '-'),
        "---------------------------------",
        "Terima kasih telah menggunakan jasa kami 🙏",
    ];
    $pesan = implode("\n", $baris);
    $waUrl = $hp ? 'https://wa.me/' . $hp . '?text=' . rawurlencode($pesan) : null;
@endphp

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> Transaksi tersimpan. Berikut notanya — bisa langsung dikirim ke WhatsApp pelanggan.
            </div>

            <div class="card">
                <div class="card-body" style="font-family: 'Courier New', monospace;">
                    <h5 class="text-center fw-bold mb-1">{{ config('app.name') }}</h5>
                    <p class="text-center text-muted small mb-3">Nota Laundry</p>
                    <hr>
                    <div class="d-flex justify-content-between"><span>No. Nota</span><span>{{ $transaksi->kode }}</span></div>
                    <div class="d-flex justify-content-between"><span>Tanggal</span><span>{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</span></div>
                    <div class="d-flex justify-content-between"><span>Nama</span><span>{{ $transaksi->nama_pelanggan }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between"><span>{{ $transaksi->layanan->nama ?? '-' }}</span><span>{{ $berat }} kg</span></div>
                    <div class="d-flex justify-content-between"><span>Harga / kg</span><span>Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>TOTAL</span><span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></div>
                    <div class="d-flex justify-content-between"><span>Pembayaran</span><span>{{ $transaksi->status_bayar === 'lunas' ? 'LUNAS' : 'BELUM BAYAR' }}</span></div>
                    <div class="d-flex justify-content-between"><span>Estimasi</span><span>{{ $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d/m/Y H:i') : '-' }}</span></div>
                    <hr>
                    <p class="text-center small mb-0">Terima kasih telah menggunakan jasa kami 🙏</p>
                </div>
                <div class="card-footer bg-white d-flex gap-2 flex-wrap">
                    @if ($waUrl)
                        <a href="{{ $waUrl }}" target="_blank" class="btn btn-success">
                            <i class="bi bi-whatsapp"></i> Kirim Nota via WhatsApp
                        </a>
                    @else
                        <button class="btn btn-success" disabled title="Nomor HP belum diisi">
                            <i class="bi bi-whatsapp"></i> Kirim via WhatsApp
                        </button>
                        <small class="text-danger align-self-center">No. HP belum diisi, edit transaksi untuk menambahkan.</small>
                    @endif
                    <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-primary ms-auto">Selesai</a>
                </div>
            </div>
        </div>
    </div>
@endsection

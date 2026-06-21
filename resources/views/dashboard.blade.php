@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3"><i class="bi bi-receipt fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Transaksi Hari Ini</div>
                        <div class="fs-4 fw-bold">{{ $stats['transaksi_hari_ini'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3"><i class="bi bi-arrow-repeat fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Sedang Diproses</div>
                        <div class="fs-4 fw-bold">{{ $stats['diproses'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3"><i class="bi bi-check2-circle fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Selesai (Belum Diambil)</div>
                        <div class="fs-4 fw-bold">{{ $stats['selesai'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3"><i class="bi bi-people fs-3"></i></div>
                    <div>
                        <div class="text-muted small">Karyawan Aktif</div>
                        <div class="fs-4 fw-bold">{{ $stats['karyawan'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Pendapatan Hari Ini</div>
                    <div class="fs-3 fw-bold text-success">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Pendapatan Bulan Ini</div>
                    <div class="fs-3 fw-bold text-success">Rp {{ number_format($stats['pendapatan_bulan_ini'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Transaksi Terbaru</strong>
            <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tgl Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->nama_pelanggan }}</td>
                            <td>{{ $trx->layanan->nama ?? '-' }}</td>
                            <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge text-bg-{{ $trx->statusBadge() }}">{{ ucfirst($trx->status) }}</span></td>
                            <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

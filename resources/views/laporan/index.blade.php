@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Periode</label>
                    <select name="periode" id="periode" class="form-select">
                        <option value="hari_ini" @selected($periode === 'hari_ini')>Hari Ini</option>
                        <option value="minggu_ini" @selected($periode === 'minggu_ini')>Minggu Ini</option>
                        <option value="bulan_ini" @selected($periode === 'bulan_ini')>Bulan Ini</option>
                        <option value="custom" @selected($periode === 'custom')>Pilih Tanggal...</option>
                    </select>
                </div>
                <div class="col-md-3 box-custom" style="{{ $periode === 'custom' ? '' : 'display:none' }}">
                    <label class="form-label small mb-1">Dari Tanggal</label>
                    <input type="date" name="tgl_dari" value="{{ $tgl_dari }}" class="form-control">
                </div>
                <div class="col-md-3 box-custom" style="{{ $periode === 'custom' ? '' : 'display:none' }}">
                    <label class="form-label small mb-1">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" value="{{ $tgl_sampai }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-funnel"></i> Tampilkan</button>
                    <a href="{{ route('laporan.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Jumlah Transaksi</div>
                <div class="fs-4 fw-bold">{{ $ringkasan['jumlah'] }}</div>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Total Pendapatan</div>
                <div class="fs-5 fw-bold text-success">Rp {{ number_format($ringkasan['total'], 0, ',', '.') }}</div>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Sudah Lunas</div>
                <div class="fs-5 fw-bold text-primary">Rp {{ number_format($ringkasan['lunas'], 0, ',', '.') }}</div>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card"><div class="card-body">
                <div class="text-muted small">Belum Bayar</div>
                <div class="fs-5 fw-bold text-danger">Rp {{ number_format($ringkasan['belum'], 0, ',', '.') }}</div>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white"><strong>{{ $label }}</strong></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tgl Masuk</th>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $trx)
                        <tr>
                            <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->nama_pelanggan }}</td>
                            <td>{{ $trx->layanan->nama ?? '-' }}</td>
                            <td>{{ rtrim(rtrim(number_format($trx->berat, 2), '0'), '.') }} {{ strtoupper($trx->layanan->satuan ?? 'kg') }}</td>
                            <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge text-bg-{{ $trx->statusBadge() }}">{{ ucfirst($trx->status) }}</span></td>
                            <td>{{ $trx->status_bayar === 'lunas' ? 'Lunas' : 'Belum' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count())
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">TOTAL</td>
                            <td colspan="3">Rp {{ number_format($ringkasan['total'], 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('periode').addEventListener('change', function () {
            const tampil = this.value === 'custom';
            document.querySelectorAll('.box-custom').forEach(el => el.style.display = tampil ? '' : 'none');
        });
    </script>
    @endpush
@endsection

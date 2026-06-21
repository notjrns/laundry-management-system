@extends('layouts.app')
@section('title', 'Data Transaksi')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('transaksi.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small mb-1">Cari (nama / kode / no HP)</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Ketik kata kunci...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach (['diproses' => 'Diproses', 'selesai' => 'Selesai', 'diambil' => 'Diambil'] as $val => $label)
                            <option value="{{ $val }}" @selected(request('status') == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">Reset</a>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-success ms-auto"><i class="bi bi-plus-lg"></i> Tambah</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Bayar</th>
                        <th>Tgl Masuk</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $trx)
                        <tr>
                            <td class="fw-semibold">{{ $trx->kode }}</td>
                            <td>{{ $trx->nama_pelanggan }}<br><small class="text-muted">{{ $trx->no_hp }}</small></td>
                            <td>{{ $trx->layanan->nama ?? '-' }}</td>
                            <td>{{ rtrim(rtrim(number_format($trx->berat, 2), '0'), '.') }} {{ strtoupper($trx->layanan->satuan ?? 'kg') }}</td>
                            <td>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td><span class="badge text-bg-{{ $trx->statusBadge() }}">{{ ucfirst($trx->status) }}</span></td>
                            <td>
                                <span class="badge text-bg-{{ $trx->status_bayar === 'lunas' ? 'success' : 'secondary' }}">
                                    {{ $trx->status_bayar === 'lunas' ? 'Lunas' : 'Belum' }}
                                </span>
                                <br><small class="text-muted">{{ ucfirst($trx->metode_bayar) }}</small>
                            </td>
                            <td>{{ $trx->tanggal_masuk->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('transaksi.nota', $trx) }}" class="btn btn-outline-success" title="Nota / WA"><i class="bi bi-whatsapp"></i></a>
                                    <a href="{{ route('transaksi.show', $trx) }}" class="btn btn-outline-secondary" title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('transaksi.edit', $trx) }}" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('transaksi.destroy', $trx) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($transaksis->hasPages())
            <div class="card-footer bg-white">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
@endsection

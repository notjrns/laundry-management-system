@extends('layouts.app')
@section('title', 'Atur Layanan')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('layanan.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small mb-1">Cari nama layanan</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="contoh: Cuci Gosok">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('layanan.index') }}" class="btn btn-outline-secondary">Reset</a>
                    <a href="{{ route('layanan.create') }}" class="btn btn-success ms-auto"><i class="bi bi-plus-lg"></i> Tambah Layanan</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Satuan</th>
                        <th>Estimasi Waktu</th>
                        <th>Harga</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($layanans as $i => $layanan)
                        <tr>
                            <td>{{ $layanans->firstItem() + $i }}</td>
                            <td class="fw-semibold">{{ $layanan->nama }}</td>
                            <td><span class="badge text-bg-secondary text-uppercase">{{ $layanan->satuan }}</span></td>
                            <td>{{ $layanan->estimasiLabel() }}</td>
                            <td>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('layanan.edit', $layanan) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('layanan.destroy', $layanan) }}" onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada layanan. Klik <strong>Tambah Layanan</strong>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($layanans->hasPages())
            <div class="card-footer bg-white">{{ $layanans->links() }}</div>
        @endif
    </div>
@endsection

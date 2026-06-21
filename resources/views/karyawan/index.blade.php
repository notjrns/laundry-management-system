@extends('layouts.app')
@section('title', 'Data Karyawan')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('karyawan.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small mb-1">Cari (nama / jabatan)</label>
                    <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Ketik kata kunci...">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Reset</a>
                    <a href="{{ route('karyawan.create') }}" class="btn btn-success ms-auto"><i class="bi bi-plus-lg"></i> Tambah Karyawan</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Tgl Masuk</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawans as $kar)
                        <tr>
                            <td class="fw-semibold">{{ $kar->nama }}</td>
                            <td>{{ $kar->jabatan ?? '-' }}</td>
                            <td>{{ $kar->no_hp ?? '-' }}</td>
                            <td>{{ $kar->alamat ?? '-' }}</td>
                            <td>{{ $kar->tanggal_masuk ? $kar->tanggal_masuk->format('d/m/Y') : '-' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $kar->status === 'aktif' ? 'success' : 'secondary' }}">
                                    {{ $kar->status === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('karyawan.edit', $kar) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('karyawan.destroy', $kar) }}" onsubmit="return confirm('Hapus karyawan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($karyawans->hasPages())
            <div class="card-footer bg-white">{{ $karyawans->links() }}</div>
        @endif
    </div>
@endsection

@extends('layouts.app')
@section('title', 'Rak ' . $rak->nama)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">{{ $rak->nama }}</h4>
            <small class="text-muted">{{ $rak->jumlah_kolom }} kolom &middot; {{ $rak->keterangan }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('rak.edit', $rak) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i> Edit Rak</a>
            <a href="{{ route('rak.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="alert alert-info d-flex align-items-center gap-2">
        <i class="bi bi-info-circle"></i>
        <span>Rak terisi <strong>otomatis</strong> dari transaksi. Order masuk → menempati kolom kosong. Saat status jadi <strong>"Diambil"</strong> → kolom otomatis kosong kembali.</span>
    </div>

    <div class="row g-3">
        @foreach ($rak->koloms as $kolom)
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 {{ $kolom->terisi ? 'border-primary' : '' }}">
                    <div class="card-header d-flex justify-content-between align-items-center
                        {{ $kolom->terisi ? 'bg-primary text-white' : 'bg-light' }}">
                        <strong>Kolom #{{ $kolom->nomor_kolom }}</strong>
                        @if ($kolom->terisi)
                            <span class="badge text-bg-light text-dark">{{ ucfirst($kolom->status) }}</span>
                        @else
                            <span class="badge text-bg-secondary">Kosong</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($kolom->terisi)
                            <p class="mb-1"><i class="bi bi-person"></i> <strong>{{ $kolom->nama_pelanggan }}</strong></p>
                            <p class="mb-1 small text-muted"><i class="bi bi-bag"></i> {{ $kolom->jenis_layanan ?? '-' }}</p>
                            <p class="mb-0 small text-muted"><i class="bi bi-clock"></i>
                                {{ $kolom->estimasi_pengambilan ? $kolom->estimasi_pengambilan->format('d/m/Y H:i') : '-' }}
                            </p>
                        @else
                            <p class="text-muted text-center my-3 mb-0"><i class="bi bi-inbox fs-3 d-block"></i> Belum ada isi</p>
                        @endif
                    </div>
                    @if ($kolom->terisi && $kolom->transaksi)
                        <div class="card-footer bg-white">
                            <a href="{{ route('transaksi.show', $kolom->transaksi) }}" class="btn btn-sm btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> Lihat Transaksi ({{ $kolom->transaksi->kode }})
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection

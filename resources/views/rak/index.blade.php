@extends('layouts.app')
@section('title', 'Rak Laundry')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('rak.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Rak</a>
    </div>

    <div class="row g-3">
        @forelse ($raks as $rak)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="fw-bold mb-1"><i class="bi bi-grid-3x3-gap text-primary"></i> {{ $rak->nama }}</h5>
                            <span class="badge text-bg-primary">{{ $rak->terisi_count }}/{{ $rak->koloms_count }} terisi</span>
                        </div>
                        @if ($rak->keterangan)
                            <p class="text-muted small mb-2">{{ $rak->keterangan }}</p>
                        @endif
                        <div class="progress mb-3" style="height: 8px;">
                            @php $persen = $rak->koloms_count ? round($rak->terisi_count / $rak->koloms_count * 100) : 0; @endphp
                            <div class="progress-bar" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <a href="{{ route('rak.show', $rak) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Lihat Kolom</a>
                        <a href="{{ route('rak.edit', $rak) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('rak.destroy', $rak) }}" onsubmit="return confirm('Hapus rak ini beserta semua kolomnya?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card"><div class="card-body text-center text-muted py-5">
                    Belum ada rak. Klik <strong>Tambah Rak</strong> untuk membuat.
                </div></div>
            </div>
        @endforelse
    </div>
@endsection

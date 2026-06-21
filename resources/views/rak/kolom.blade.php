@extends('layouts.app')
@section('title', 'Isi Kolom #' . $rakKolom->nomor_kolom)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('kolom.update', $rakKolom) }}">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header bg-white">
                        <strong>{{ $rakKolom->rak->nama }} — Kolom #{{ $rakKolom->nomor_kolom }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Ambil dari Data Transaksi</label>
                            <select id="transaksi_select" name="transaksi_id" class="form-select">
                                <option value="">-- Isi manual / pilih transaksi --</option>
                                @foreach ($transaksis as $trx)
                                    <option value="{{ $trx->id }}"
                                        data-nama="{{ $trx->nama_pelanggan }}"
                                        data-layanan="{{ $trx->layanan->nama ?? '' }}"
                                        data-estimasi="{{ $trx->estimasi_selesai ? $trx->estimasi_selesai->format('Y-m-d\TH:i') : '' }}"
                                        data-status="{{ $trx->status }}"
                                        @selected(old('transaksi_id', $rakKolom->transaksi_id) == $trx->id)>
                                        {{ $trx->kode }} - {{ $trx->nama_pelanggan }} ({{ $trx->layanan->nama ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pilih transaksi untuk mengisi data di bawah secara otomatis.</div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control"
                                value="{{ old('nama_pelanggan', $rakKolom->nama_pelanggan) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Layanan</label>
                            <input type="text" id="jenis_layanan" name="jenis_layanan" class="form-control"
                                placeholder="contoh: Cuci Kering" value="{{ old('jenis_layanan', $rakKolom->jenis_layanan) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estimasi Pengambilan</label>
                            <input type="datetime-local" id="estimasi_pengambilan" name="estimasi_pengambilan" class="form-control"
                                value="{{ old('estimasi_pengambilan', $rakKolom->estimasi_pengambilan ? $rakKolom->estimasi_pengambilan->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                @foreach (['diproses' => 'Diproses', 'selesai' => 'Selesai', 'diambil' => 'Diambil'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('status', $rakKolom->status ?? 'diproses') == $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Isi Kolom</button>
                        <a href="{{ route('rak.show', $rakKolom->rak_id) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('transaksi_select').addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (!opt.value) return;
            document.getElementById('nama_pelanggan').value = opt.dataset.nama || '';
            document.getElementById('jenis_layanan').value = opt.dataset.layanan || '';
            document.getElementById('estimasi_pengambilan').value = opt.dataset.estimasi || '';
            if (opt.dataset.status) document.getElementById('status').value = opt.dataset.status;
        });
    </script>
    @endpush
@endsection

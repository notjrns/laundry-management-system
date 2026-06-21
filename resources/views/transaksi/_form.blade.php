@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><strong><i class="bi bi-person"></i> Data Pelanggan</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pelanggan" class="form-control"
                        value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP (WhatsApp)</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                        value="{{ old('no_hp', $transaksi->no_hp ?? '') }}">
                    <div class="form-text">Diperlukan untuk mengirim nota via WhatsApp.</div>
                </div>
                <div class="mb-0">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $transaksi->alamat ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><strong><i class="bi bi-bag"></i> Detail Laundry</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Layanan <span class="text-danger">*</span></label>
                    <select name="layanan_id" id="layanan_id" class="form-select" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach ($layanans as $layanan)
                            <option value="{{ $layanan->id }}" data-harga="{{ $layanan->harga }}"
                                @selected(old('layanan_id', $transaksi->layanan_id ?? '') == $layanan->id)>
                                {{ $layanan->nama }} (Rp {{ number_format($layanan->harga, 0, ',', '.') }}/{{ $layanan->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" min="0.1" name="berat" id="berat" class="form-control"
                        value="{{ old('berat', $transaksi->berat ?? '') }}" required>
                </div>
                <div class="alert alert-primary d-flex justify-content-between mb-0">
                    <span>Estimasi Total:</span>
                    <strong id="total_preview">Rp 0</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white"><strong><i class="bi bi-calendar"></i> Waktu & Status</strong></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control"
                        value="{{ old('tanggal_masuk', isset($transaksi) ? $transaksi->tanggal_masuk->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estimasi Selesai</label>
                    <input type="datetime-local" name="estimasi_selesai" class="form-control"
                        value="{{ old('estimasi_selesai', isset($transaksi) && $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="row">
                    <div class="col-6 mb-0">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach (['diproses' => 'Diproses', 'selesai' => 'Selesai', 'diambil' => 'Diambil'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('status', $transaksi->status ?? 'diproses') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mb-0">
                        <label class="form-label">Pembayaran</label>
                        <select name="status_bayar" class="form-select">
                            @foreach (['belum' => 'Belum Bayar', 'lunas' => 'Lunas'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('status_bayar', $transaksi->status_bayar ?? 'belum') == $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white"><strong><i class="bi bi-pencil"></i> Catatan</strong></div>
            <div class="card-body">
                <textarea name="catatan" rows="4" class="form-control" placeholder="Catatan tambahan (opsional)">{{ old('catatan', $transaksi->catatan ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel ?? 'Simpan' }}</button>
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>

@push('scripts')
<script>
    const selLayanan = document.getElementById('layanan_id');
    const inputBerat = document.getElementById('berat');
    const totalPreview = document.getElementById('total_preview');

    function hitungTotal() {
        const opt = selLayanan.options[selLayanan.selectedIndex];
        const harga = opt ? parseFloat(opt.dataset.harga || 0) : 0;
        const berat = parseFloat(inputBerat.value || 0);
        const total = Math.round(harga * berat);
        totalPreview.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    selLayanan.addEventListener('change', hitungTotal);
    inputBerat.addEventListener('input', hitungTotal);
    hitungTotal();
</script>
@endpush

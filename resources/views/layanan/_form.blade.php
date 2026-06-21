@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Layanan <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="contoh: Cuci Gosok"
                    value="{{ old('nama', $layanan->nama ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                <select name="satuan" class="form-select">
                    @foreach (['kg' => 'Kg', 'pcs' => 'Pcs'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('satuan', $layanan->satuan ?? 'kg') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estimasi Waktu <span class="text-danger">*</span></label>
                <input type="number" min="1" name="estimasi_nilai" class="form-control"
                    value="{{ old('estimasi_nilai', $layanan->estimasi_nilai ?? 1) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Satuan Waktu <span class="text-danger">*</span></label>
                <select name="estimasi_satuan" class="form-select">
                    @foreach (['jam' => 'Jam', 'hari' => 'Hari'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('estimasi_satuan', $layanan->estimasi_satuan ?? 'hari') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" min="0" name="harga" class="form-control" placeholder="contoh: 6000"
                    value="{{ old('harga', $layanan->harga ?? '') }}" required>
                <div class="form-text">Harga per satuan (per Kg / per Pcs).</div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel ?? 'Simpan' }}</button>
        <a href="{{ route('layanan.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</div>

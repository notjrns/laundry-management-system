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
                <label class="form-label">Nama Rak <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="contoh: Rak A"
                    value="{{ old('nama', $rak->nama ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jumlah Kolom <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_kolom" min="1" max="200" class="form-control"
                    value="{{ old('jumlah_kolom', $rak->jumlah_kolom ?? 20) }}" required>
                <div class="form-text">Kolom akan dibuat otomatis sebanyak angka ini (mis. 20).</div>
            </div>
            <div class="col-12">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" rows="2" class="form-control">{{ old('keterangan', $rak->keterangan ?? '') }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel ?? 'Simpan' }}</button>
        <a href="{{ route('rak.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</div>

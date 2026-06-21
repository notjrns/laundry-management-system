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
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $karyawan->nama ?? '') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" placeholder="contoh: Kasir, Operator"
                    value="{{ old('jabatan', $karyawan->jabatan ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $karyawan->no_hp ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" class="form-control"
                    value="{{ old('tanggal_masuk', isset($karyawan) && $karyawan->tanggal_masuk ? $karyawan->tanggal_masuk->format('Y-m-d') : '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach (['aktif' => 'Aktif', 'nonaktif' => 'Non-Aktif'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('status', $karyawan->status ?? 'aktif') == $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $karyawan->alamat ?? '') }}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> {{ $submitLabel ?? 'Simpan' }}</button>
        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</div>

# 4. Blade & View — Tampilan

## 4.1 Apa itu Blade?

Blade = mesin template Laravel. File-nya berakhiran **`.blade.php`** dan ada di `resources/views/`.
Blade memungkinkan menulis HTML + menyisipkan data PHP dengan sintaks yang singkat.

---

## 4.2 Sintaks Dasar Blade

| Sintaks | Fungsi | Contoh |
|---------|--------|--------|
| `{{ $x }}` | Tampilkan data (aman dari hacking/XSS) | `{{ $transaksi->nama_pelanggan }}` |
| `{!! $x !!}` | Tampilkan data tanpa escape (HTML mentah) | jarang dipakai |
| `@if / @else / @endif` | Percabangan | lihat bawah |
| `@foreach / @endforeach` | Perulangan | lihat bawah |
| `@forelse / @empty / @endforelse` | Perulangan + handle kosong | lihat bawah |
| `@php ... @endphp` | Tulis PHP biasa | hitung sesuatu |
| `{{-- ... --}}` | Komentar (tidak muncul di HTML) | |

### Contoh percabangan:
```blade
@if ($transaksi->status === 'diproses')
    <span class="badge bg-warning">Diproses</span>
@else
    <span class="badge bg-success">Selesai</span>
@endif
```

### Contoh perulangan (dengan handle kosong) — dipakai di semua tabel:
```blade
@forelse ($transaksis as $trx)
    <tr>
        <td>{{ $trx->kode }}</td>
        <td>{{ $trx->nama_pelanggan }}</td>
    </tr>
@empty
    <tr><td colspan="5">Belum ada data.</td></tr>
@endforelse
```
- `@forelse` = looping; kalau datanya **kosong**, jalankan blok `@empty`.

---

## 4.3 Layout (Template Induk) — Hindari Copy-Paste

Daripada nulis sidebar+header di tiap halaman, kita bikin **1 layout induk**:
[resources/views/layouts/app.blade.php](../resources/views/layouts/app.blade.php).

Di dalamnya ada "lubang" yang akan diisi tiap halaman:
```blade
<title>@yield('title', 'Dashboard')</title>   {{-- lubang judul --}}
...
<main>
    @yield('content')   {{-- lubang isi halaman --}}
</main>
@stack('scripts')       {{-- lubang untuk JS tambahan --}}
@stack('styles')        {{-- lubang untuk CSS tambahan --}}
```

Lalu halaman anak "menempel" ke layout, contoh [dashboard.blade.php](../resources/views/dashboard.blade.php):
```blade
@extends('layouts.app')          {{-- pakai layout induk --}}
@section('title', 'Dashboard')   {{-- isi lubang judul --}}

@section('content')              {{-- isi lubang konten --}}
    <h1>Isi halaman di sini</h1>
@endsection
```

| Directive | Fungsi |
|-----------|--------|
| `@extends('layouts.app')` | "Saya pakai layout app" |
| `@section('content') ... @endsection` | Isi konten halaman |
| `@yield('content')` | Lubang di layout yang akan diisi |
| `@push('scripts') ... @endpush` | Tambah JS/CSS khusus halaman |
| `@stack('scripts')` | Tempat menampung yang di-`@push` |

> Ada 2 layout di project: `layouts/app.blade.php` (halaman admin, ada sidebar) dan
> `layouts/guest.blade.php` (halaman login/register, polos di tengah).

---

## 4.4 Partial / Include (Form Dipakai Ulang)

Form **Tambah** dan **Edit** isinya hampir sama. Daripada nulis 2x, kita pisah jadi 1 file partial:
`transaksi/_form.blade.php` (awalan `_` cuma penanda partial), lalu dipanggil:

```blade
{{-- create.blade.php --}}
<form method="POST" action="{{ route('transaksi.store') }}">
    @csrf
    @include('transaksi._form', ['submitLabel' => 'Simpan'])
</form>
```
```blade
{{-- edit.blade.php --}}
<form method="POST" action="{{ route('transaksi.update', $transaksi) }}">
    @csrf
    @method('PUT')
    @include('transaksi._form', ['submitLabel' => 'Simpan Perubahan'])
</form>
```

- `@include('x', [...])` = sisipkan file lain + kirim variabel.

---

## 4.5 Form, CSRF, dan Method Spoofing

```blade
<form method="POST" action="{{ route('transaksi.store') }}">
    @csrf                {{-- WAJIB: token keamanan anti-pemalsuan --}}
    ...
</form>
```

- **`@csrf`** = token keamanan. **Wajib** di setiap form POST/PUT/DELETE. Kalau lupa → error 419.
- HTML form hanya bisa GET & POST. Untuk **update/hapus** pakai trik `@method`:
  ```blade
  <form method="POST" action="{{ route('transaksi.destroy', $trx) }}">
      @csrf
      @method('DELETE')    {{-- pura-pura DELETE --}}
      <button>Hapus</button>
  </form>
  ```

---

## 4.6 Menampilkan Error Validasi & Data Lama

```blade
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- old() = isi ulang nilai sebelumnya kalau validasi gagal --}}
<input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', $transaksi->nama_pelanggan ?? '') }}">
```

- `$errors` = otomatis tersedia kalau validasi gagal.
- `old('nama')` = nilai yang tadi diketik user (biar gak hilang saat error).
- `$transaksi->... ?? ''` = kalau halaman edit, isi dengan data lama; kalau tambah, kosong.

---

## 4.7 Directive Berguna Lain (dipakai di project)

```blade
@auth ... @endauth          {{-- tampil kalau sudah login --}}
@guest ... @endguest        {{-- tampil kalau belum login --}}
@selected(kondisi)          {{-- tambah atribut selected di <option> --}}
@style(['width: 50%'])      {{-- bikin atribut style dengan aman --}}
@csrf  @method('PUT')       {{-- token & method spoofing --}}
```

Contoh `@selected` di dropdown (biar pilihan lama tetap terpilih saat edit):
```blade
<option value="{{ $layanan->id }}" @selected(old('layanan_id', $transaksi->layanan_id ?? '') == $layanan->id)>
    {{ $layanan->nama }}
</option>
```

---

## 4.8 Bootstrap (Styling)

Project pakai **Bootstrap 5** lewat CDN (lihat `<head>` di `layouts/app.blade.php`). Jadi cukup pakai
**class** untuk styling tanpa nulis CSS:

| Class | Efek |
|-------|------|
| `btn btn-primary` | tombol biru |
| `card` | kotak/panel |
| `table table-hover` | tabel |
| `badge text-bg-success` | label hijau |
| `row` + `col-md-6` | grid 2 kolom |
| `alert alert-danger` | kotak peringatan merah |
| `form-control` / `form-select` | input / dropdown |

> Butuh internet karena Bootstrap diambil dari CDN.

---

## ✅ Rangkuman Bab Ini
- Blade = HTML + data. `{{ }}` tampilkan data, `@if/@foreach/@forelse` logika.
- Layout induk (`@extends`/`@yield`/`@section`) biar gak copy-paste.
- `@include` untuk form yang dipakai ulang (create & edit).
- `@csrf` wajib di form; `@method('PUT'/'DELETE')` untuk update/hapus.
- `$errors` & `old()` untuk validasi.
- Styling pakai class Bootstrap.

➡️ Lanjut: [05-bedah-fitur.md](05-bedah-fitur.md)

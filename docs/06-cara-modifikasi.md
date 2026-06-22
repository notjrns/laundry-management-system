# 6. Cara Modifikasi Web (Skenario Live Coding) ⭐

> **Bab paling penting buat tes praktek.** Tiap skenario = langkah lengkap + file yang disentuh.
> Hafalkan **pola**-nya, bukan kode persisnya.

📌 **Aturan emas:** kalau menyangkut **data baru**, hampir selalu menyentuh 5 tempat:
**Migrasi → Model (`$fillable`) → Controller (validasi) → View form → View tampilan.**

---

## 🔧 Skenario 1: Menambah Kolom Baru di Tabel (mis. "email pelanggan" di transaksi)

**Langkah:**

1. **Migrasi** — tambah kolom di [migrasi transaksis](../database/migrations/2024_01_01_000003_create_transaksis_table.php):
   ```php
   $table->string('email_pelanggan')->nullable();
   ```
2. **Jalankan ulang migrasi** (data contoh kehapus, aman):
   ```bash
   php artisan migrate:fresh --seed
   ```
   > Atau bikin migrasi baru biar data tak hilang (lihat Skenario 8).
3. **Model** — tambah ke `$fillable` di [Transaksi.php](../app/Models/Transaksi.php):
   ```php
   protected $fillable = [..., 'email_pelanggan'];
   ```
4. **Controller** — tambah validasi di `validateData()` [TransaksiController](../app/Http/Controllers/TransaksiController.php):
   ```php
   'email_pelanggan' => ['nullable', 'email'],
   ```
5. **View form** — tambah input di [transaksi/_form.blade.php](../resources/views/transaksi/_form.blade.php):
   ```blade
   <div class="mb-3">
       <label class="form-label">Email Pelanggan</label>
       <input type="email" name="email_pelanggan" class="form-control"
              value="{{ old('email_pelanggan', $transaksi->email_pelanggan ?? '') }}">
   </div>
   ```
6. **View tampilan** — tampilkan di [show.blade.php](../resources/views/transaksi/show.blade.php) / index:
   ```blade
   <tr><th>Email</th><td>{{ $transaksi->email_pelanggan ?? '-' }}</td></tr>
   ```

> ✅ Ingat 5 langkah: **migrasi → fillable → validasi → form → tampilan**. Ini contoh nyata yang
> sudah kita lakukan saat menambah `metode_bayar` & estimasi layanan.

---

## 🔧 Skenario 2: Menampilkan Kolom yang Sudah Ada di Tabel/Daftar

(Kasus nyata: dulu "alamat karyawan" sudah ada di DB tapi belum tampil.)

Cukup edit **view index** saja — tambah `<th>` di header dan `<td>` di body.
Contoh [karyawan/index.blade.php](../resources/views/karyawan/index.blade.php):
```blade
{{-- di <thead> --}}
<th>Alamat</th>
{{-- di <tbody>, di dalam @foreach --}}
<td>{{ $kar->alamat ?? '-' }}</td>
```
> ⚠️ Jangan lupa sesuaikan angka `colspan` di baris "data kosong" kalau jumlah kolom berubah.

---

## 🔧 Skenario 3: Mengubah Aturan Validasi

Semua validasi ada di fungsi `validateData()` / `validate()` di controller terkait.
Contoh: bikin No HP wajib diisi → di [TransaksiController](../app/Http/Controllers/TransaksiController.php):
```php
// dari:
'no_hp' => ['nullable', 'string', 'max:20'],
// jadi:
'no_hp' => ['required', 'string', 'max:20'],
```
Mau batasi berat maksimal 50 kg? `'berat' => ['required', 'numeric', 'min:0.1', 'max:50'],`

---

## 🔧 Skenario 4: Menambah Menu Baru (mis. menu "Pelanggan")

(Pola ini persis seperti saat kita menambah menu **Atur Layanan**.)

1. **Buat migrasi + model + tabel** (lihat Skenario 6).
2. **Buat Controller:**
   ```bash
   php artisan make:controller PelangganController --resource
   ```
   atau tulis manual meniru [KaryawanController](../app/Http/Controllers/KaryawanController.php).
3. **Daftarkan route** di [routes/web.php](../routes/web.php) (di dalam grup `auth`):
   ```php
   Route::resource('pelanggan', PelangganController::class);
   ```
4. **Buat folder view** `resources/views/pelanggan/` berisi `index`, `create`, `edit`, `_form`
   (copy dari folder `karyawan` lalu sesuaikan).
5. **Tambah link di sidebar** [layouts/app.blade.php](../resources/views/layouts/app.blade.php):
   ```blade
   <a href="{{ route('pelanggan.index') }}"
      class="{{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
       <i class="bi bi-person-lines-fill"></i> Pelanggan
   </a>
   ```

> 5 langkah: **migrasi/model → controller → route → view → link sidebar.**

---

## 🔧 Skenario 5: Menambah Item di Sidebar / Mengubah Urutan Menu

Buka [layouts/app.blade.php](../resources/views/layouts/app.blade.php), bagian `<nav class="sidebar">`.
Tiap menu = satu baris `<a>`. Tinggal pindahkan urutannya atau tambah baris baru.
Ikon dari Bootstrap Icons (`<i class="bi bi-xxx">`), daftar ikon: https://icons.getbootstrap.com

---

## 🔧 Skenario 6: Membuat Tabel + Model Baru dari Nol

```bash
php artisan make:model Pelanggan -m
```
- `-m` = sekalian bikin migrasinya.
- Edit migrasi di `database/migrations/...create_pelanggans_table.php` (tambah kolom).
- Edit model `app/Models/Pelanggan.php` (isi `$fillable`).
- Jalankan `php artisan migrate`.

---

## 🔧 Skenario 7: Menambah Pilihan di Dropdown ENUM (mis. status "dibatalkan")

1. **Migrasi** — ubah enum di kolom status:
   ```php
   $table->enum('status', ['diproses', 'selesai', 'diambil', 'dibatalkan'])->default('diproses');
   ```
2. `php artisan migrate:fresh --seed`
3. **Validasi** controller: `'status' => ['required', 'in:diproses,selesai,diambil,dibatalkan'],`
4. **View form** ([_form.blade.php](../resources/views/transaksi/_form.blade.php)) — tambah ke array pilihan:
   ```blade
   @foreach (['diproses'=>'Diproses','selesai'=>'Selesai','diambil'=>'Diambil','dibatalkan'=>'Dibatalkan'] as $val => $label)
   ```

---

## 🔧 Skenario 8: Mengubah Tabel TANPA Menghapus Data (migrasi tambahan)

Kalau sudah ada data penting, jangan `migrate:fresh`. Buat migrasi baru:
```bash
php artisan make:migration tambah_kolom_diskon_ke_transaksis --table=transaksis
```
Isi:
```php
public function up(): void {
    Schema::table('transaksis', function (Blueprint $table) {
        $table->unsignedInteger('diskon')->default(0)->after('total_harga');
    });
}
public function down(): void {
    Schema::table('transaksis', function (Blueprint $table) {
        $table->dropColumn('diskon');
    });
}
```
Lalu `php artisan migrate` (tanpa fresh). Data lama aman.

---

## 🔧 Skenario 9: Mengubah Tampilan (Warna/Teks/Judul)

- **Nama aplikasi:** ubah `APP_NAME` di `.env` → `php artisan config:clear`.
- **Judul halaman:** ubah `@section('title', '...')` di view.
- **Teks tombol/label:** langsung edit di file `.blade.php` terkait.
- **Warna:** ganti class Bootstrap (`btn-primary` → `btn-success`, dll) atau edit `<style>` di
  [layouts/app.blade.php](../resources/views/layouts/app.blade.php).

---

## 🔧 Skenario 10: Mengubah Logika Bisnis (mis. tambah diskon ke total)

Total dihitung di `store()`/`update()` [TransaksiController](../app/Http/Controllers/TransaksiController.php):
```php
// dari:
$data['total_harga'] = (int) round($layanan->harga * (float) $data['berat']);
// jadi (kurangi diskon):
$subtotal = $layanan->harga * (float) $data['berat'];
$data['total_harga'] = (int) round($subtotal - ($data['diskon'] ?? 0));
```
Jangan lupa: tambah kolom `diskon` (Skenario 1/8) + input di form + tampilkan di nota.

---

## 🔧 Skenario 11: Menghapus Sesuatu (kolom / menu / fitur)

**Hapus kolom dari tabel** (buat migrasi baru biar data lain aman):
```bash
php artisan make:migration hapus_kolom_alamat_dari_transaksis --table=transaksis
```
```php
public function up(): void {
    Schema::table('transaksis', fn (Blueprint $t) => $t->dropColumn('alamat'));
}
```
Lalu `php artisan migrate`. Setelah itu hapus juga: `alamat` dari `$fillable` (model),
validasinya (controller), input di form, dan tampilannya di view.

**Hapus menu:** hapus baris `<a>` di sidebar + route di `web.php` + (opsional) controller & view-nya.

---

## 🔧 Skenario 12: Dropdown yang Mengambil Data dari Tabel Lain (relasi)

Contoh: di form transaksi mau pilih **petugas** dari tabel `karyawans`.

1. **Migrasi**: tambah FK `$table->foreignId('karyawan_id')->nullable()->constrained();`
2. **Model** `Transaksi`: tambah `karyawan_id` ke `$fillable` + relasi:
   ```php
   public function karyawan(): BelongsTo { return $this->belongsTo(Karyawan::class); }
   ```
3. **Controller** `create()`/`edit()`: kirim daftar karyawan ke view:
   ```php
   $karyawans = Karyawan::orderBy('nama')->get();
   return view('transaksi.create', compact('layanans', 'karyawans'));
   ```
4. **Form** (`_form.blade.php`): dropdown-nya:
   ```blade
   <select name="karyawan_id" class="form-select">
       <option value="">-- Pilih Petugas --</option>
       @foreach ($karyawans as $k)
           <option value="{{ $k->id }}" @selected(old('karyawan_id', $transaksi->karyawan_id ?? '') == $k->id)>
               {{ $k->nama }}
           </option>
       @endforeach
   </select>
   ```
5. **Validasi**: `'karyawan_id' => ['nullable', 'exists:karyawans,id'],`
6. **Tampilkan**: `{{ $transaksi->karyawan->nama ?? '-' }}` (pakai relasi).

> Ini persis pola dropdown **layanan** yang sudah ada — tinggal tiru.

---

## 🔧 Skenario 13: Mengubah Format Tampilan (Tanggal & Rupiah)

Semua dilakukan di **view** (Blade), tidak perlu sentuh database.

- **Tanggal:** `{{ $transaksi->tanggal_masuk->format('d/m/Y') }}` → ubah polanya:
  `'d-m-Y'` (21-06-2026), `'d M Y'` (21 Jun 2026), `->translatedFormat('d F Y')` (21 Juni 2026).
- **Rupiah:** `Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}` → hasil `Rp 75.000`.
  - arg ke-2 = jumlah angka desimal, ke-3 = pemisah desimal, ke-4 = pemisah ribuan.

---

## 🔧 Skenario 14: Mengubah Urutan & Jumlah Per Halaman

Di **controller** `index()`:
```php
Transaksi::latest()->paginate(10);            // urut terbaru, 10 per halaman
// ganti urutan:
Transaksi::orderBy('nama_pelanggan')->paginate(25);   // urut nama A-Z, 25 per halaman
Transaksi::orderBy('total_harga', 'desc')->paginate(10); // termahal dulu
```
- `latest()` = terbaru dulu. `oldest()` = terlama dulu. `orderBy('kolom', 'asc'/'desc')` = bebas.

---

## 🔧 Skenario 15: Upload Gambar/Foto (mis. foto karyawan)

1. **Migrasi**: `$table->string('foto')->nullable();` (simpan nama file-nya saja).
2. **`$fillable`**: tambah `'foto'`.
3. **Form**: tambah `enctype` + input file:
   ```blade
   <form method="POST" action="..." enctype="multipart/form-data">
       <input type="file" name="foto" class="form-control" accept="image/*">
   ```
4. **Controller** `store()`:
   ```php
   if ($request->hasFile('foto')) {
       $data['foto'] = $request->file('foto')->store('karyawan', 'public');
   }
   ```
5. **Sekali saja:** `php artisan storage:link` (biar file di storage bisa diakses publik).
6. **Tampilkan:** `<img src="{{ asset('storage/' . $kar->foto) }}" width="60">`

---

## 🧭 PETA KEPUTUSAN — "Mau ubah X, sentuh file mana?"

| Mau ubah... | Sentuh file |
|-------------|-------------|
| Struktur tabel (kolom) | `database/migrations/...` |
| Kolom boleh diisi | `app/Models/<Nama>.php` → `$fillable` |
| Aturan input | Controller → `validate()` / `validateData()` |
| Logika hitung/proses | Controller (fungsi `store`/`update`) |
| Otomatisasi rak | `app/Observers/TransaksiObserver.php` |
| Daftar URL/menu | `routes/web.php` |
| Tampilan halaman | `resources/views/.../*.blade.php` |
| Menu sidebar | `resources/views/layouts/app.blade.php` |
| Data awal | `database/seeders/DatabaseSeeder.php` |
| Nama app / koneksi DB | `.env` |
| Warna/CSS global | `<style>` di `layouts/app.blade.php` |

---

## 🌟 PRINSIP UNIVERSAL (biar bisa ubah APAPUN, walau gak ada di daftar)

Tiap permintaan ubahan, tanya 3 hal ini ke diri sendiri:

1. **Ini soal DATA atau TAMPILAN?**
   - Cuma tampilan (warna, teks, format, urutan, kolom yang ditampilkan) → **cukup edit view/controller**, TIDAK perlu migrasi.
   - Ada data/kolom baru → ikut **"aturan emas 5 langkah"** (migrasi → fillable → validasi → form → tampilan).
2. **Ini di LAYER mana?** Cocokkan dengan tabel **Peta Keputusan** di atas.
3. **Apakah perlu jalankan perintah?** Kalau ubah struktur tabel → `migrate`. Kalau ubah `.env` → `config:clear`. Kalau view gak update → `view:clear`.

> 🎯 Dengan 3 pertanyaan ini, **tindakan apa pun** bisa kamu pecah jadi langkah-langkah yang sudah
> kamu kuasai. Tidak ada modifikasi yang "baru" — semua kombinasi dari pola yang sama.

---

## ⚡ Perintah yang Wajib Hafal saat Ngoding

| Perintah | Kapan dipakai |
|----------|---------------|
| `php artisan serve` | menjalankan web |
| `php artisan migrate` | menerapkan migrasi baru (data lama aman) |
| `php artisan migrate:fresh --seed` | reset total tabel + isi ulang data awal |
| `php artisan make:controller XController --resource` | bikin controller CRUD |
| `php artisan make:model X -m` | bikin model + migrasi |
| `php artisan make:migration nama --table=xx` | bikin migrasi untuk ubah tabel |
| `php artisan route:list` | lihat semua route |
| `php artisan config:clear` | setelah ubah `.env` |
| `php artisan view:clear` | kalau view "ngeyel" gak update |

---

## 🧯 Kalau Error — Cek Cepat

| Error | Penyebab umum | Solusi |
|-------|---------------|--------|
| Data tidak tersimpan | Kolom lupa di `$fillable` | tambah di model |
| `419 Page Expired` | Form lupa `@csrf` | tambah `@csrf` |
| `Column not found` | Migrasi belum jalan | `php artisan migrate` |
| `Route [x] not defined` | Nama route salah/route belum dibuat | cek `routes/web.php` |
| Halaman putih/500 | Cek pesan di `storage/logs/laravel.log` | baca errornya |
| View gak berubah | cache | `php artisan view:clear` |
| Error koneksi DB | MySQL mati / `.env` salah | nyalakan MySQL, cek `.env` |

➡️ Lanjut: [07-latihan-soal.md](07-latihan-soal.md)

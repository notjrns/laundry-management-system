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

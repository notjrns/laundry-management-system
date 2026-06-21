# 🧺 Sistem Manajemen Laundry (Laravel 11)

Aplikasi web untuk admin laundry: kelola transaksi, rak, karyawan, dan laporan.
Dibuat dengan **Laravel 11 + MySQL + Bootstrap 5**.

## ✨ Fitur

1. **Home** — halaman selamat datang + pilihan Login / Register
2. **Register & Login** — akses khusus admin
3. **Dashboard** — statistik transaksi, pendapatan, karyawan
4. **Tambah Transaksi** — sekalian simpan data pelanggan, total otomatis (jumlah × harga), estimasi selesai otomatis dari paket, metode bayar (cash/transfer), **kirim nota via WhatsApp**
5. **Data Transaksi** — daftar, cari, edit, hapus
6. **Rak** — buat rak (otomatis sejumlah kolom), isi kolom dari data transaksi, edit, kosongkan isi kolom
7. **Atur Layanan** — admin kelola paket layanan (nama, satuan kg/pcs, estimasi jam/hari, harga)
8. **Data Karyawan** — tambah, edit, hapus
9. **Laporan** — filter (hari ini / minggu / bulan / rentang tanggal) + **export PDF**

---

## 🖥️ Cara Menjalankan di Windows (XAMPP)

### 1. Install yang dibutuhkan
- **XAMPP** (PHP 8.2+ & MySQL) → https://www.apachefriends.org
- **Composer** → https://getcomposer.org/download

> Pastikan saat install XAMPP, PHP-nya versi **8.2 atau lebih baru**.

### 2. Taruh folder project
Salin folder ini ke mana saja (mis. `C:\laravel\laundry`).
**Folder `vendor` tidak ikut** — akan dibuat di langkah berikut.

### 3. Nyalakan XAMPP
Buka **XAMPP Control Panel** → Start **Apache** & **MySQL**.

### 4. Buat database
Buka http://localhost/phpmyadmin → buat database baru bernama:
```
laundry_db
```

### 5. Install dependency & setup
Buka **CMD/Terminal** di dalam folder project, lalu jalankan:

```bash
composer install
copy .env.example .env       # (Windows)  -- atau: cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

> `migrate --seed` akan membuat semua tabel + akun admin default + contoh data.

### 6. Jalankan aplikasi
```bash
php artisan serve
```
Buka browser ke **http://localhost:8000**

---

## 🔑 Akun Admin Default

| Email | Password |
|-------|----------|
| `admin@laundry.test` | `password` |

> Atau klik **Register** di halaman Home untuk membuat akun admin baru.

---

## ⚙️ Konfigurasi Database (`.env`)

Default sudah cocok untuk XAMPP:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_db
DB_USERNAME=root
DB_PASSWORD=
```
Kalau MySQL XAMPP kamu pakai password, isi `DB_PASSWORD`.

---

## 📂 Struktur Penting

```
app/Http/Controllers/   -> logika tiap menu
app/Models/             -> model database
database/migrations/    -> struktur tabel
database/seeders/       -> data awal (admin + layanan)
resources/views/        -> tampilan (Blade + Bootstrap)
routes/web.php          -> daftar route/URL
```

## 🛠️ Catatan
- Tampilan pakai **Bootstrap via CDN** → butuh koneksi internet saat membuka halaman.
- Nota WhatsApp memakai link `wa.me` (gratis, tanpa API) → membuka WhatsApp dengan teks nota otomatis.
- Export laporan PDF memakai package `barryvdh/laravel-dompdf`.

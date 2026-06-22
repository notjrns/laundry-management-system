# 1. Dasar Laravel — MVC, Alur Request, Struktur Folder

## 1.1 Apa itu Laravel?

Laravel adalah **framework PHP**. Bayangkan kamu mau bikin rumah:
- Tanpa framework = bikin batu bata sendiri, campur semen sendiri (ngoding semua dari nol).
- Pakai Laravel = sudah disediakan pondasi, dinding, pintu standar — kamu tinggal susun.

Laravel sudah menyiapkan: sistem login, koneksi database, routing URL, keamanan (CSRF),
template HTML, dll. Kita tinggal pakai dengan **aturan yang rapi**.

---

## 1.2 Konsep MVC (WAJIB paham)

MVC = cara memisahkan kode jadi 3 bagian biar rapi dan gampang diubah:

```
        (1) Browser minta halaman
             │
             ▼
   ┌──────────────────┐
   │   ROUTE           │  "URL /transaksi dilayani oleh fungsi index() di TransaksiController"
   │  routes/web.php   │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐
   │   CONTROLLER      │  OTAK. Ambil data dari Model, lalu kirim ke View.
   │ app/Http/...      │
   └───┬──────────┬───┘
       ▼          ▼
 ┌─────────┐  ┌──────────┐
 │  MODEL  │  │  VIEW    │
 │ (data)  │  │(tampilan)│
 │app/Models│ │resources │
 └────┬────┘  │ /views   │
      ▼       └────┬─────┘
  Database         ▼
              HTML ke browser
```

| Bagian | Tugas | Lokasi di project | Contoh |
|--------|-------|-------------------|--------|
| **Model** | Mewakili tabel, ambil/simpan data | `app/Models/` | `Transaksi.php` |
| **View** | Tampilan HTML yang dilihat user | `resources/views/` | `transaksi/index.blade.php` |
| **Controller** | Logika: terima request, olah data, pilih view | `app/Http/Controllers/` | `TransaksiController.php` |

**Analogi restoran:**
- **Route** = daftar menu (pesanan "nasi goreng" diarahkan ke koki tertentu)
- **Controller** = pelayan (terima pesanan, koordinasi)
- **Model** = gudang bahan (ambil bahan dari dapur/database)
- **View** = piring saji (tampilan akhir ke pelanggan)

---

## 1.3 Alur Request Lengkap (CONTOH NYATA)

Misal admin buka **http://localhost:8000/transaksi** (lihat daftar transaksi):

1. **Browser** kirim request ke URL `/transaksi`.
2. **`routes/web.php`** mencocokkan URL → menemukan:
   ```php
   Route::resource('transaksi', TransaksiController::class);
   ```
   Untuk URL `/transaksi` (GET), berarti memanggil fungsi `index()`.
3. **Middleware `auth`** mengecek: apakah sudah login? Kalau belum → dilempar ke `/login`.
4. **`TransaksiController@index()`** dijalankan:
   ```php
   $transaksis = Transaksi::with('layanan')->latest()->paginate(10);
   return view('transaksi.index', compact('transaksis'));
   ```
   - Baris 1: pakai **Model** `Transaksi` ambil data dari database.
   - Baris 2: kirim data itu ke **View** `transaksi/index.blade.php`.
5. **View** mengubah data jadi HTML (tabel).
6. **Response** HTML dikirim balik ke browser → admin lihat tabel.

> 🎯 Kalau penguji nanya "jelaskan alurnya", jawab pakai 6 langkah ini. Ganti contohnya sesuai fitur yang ditanya.

---

## 1.4 Struktur Folder Project (yang PENTING saja)

```
laundry-management-system/
├── app/
│   ├── Http/Controllers/   ← OTAK tiap menu (logika)
│   ├── Models/             ← representasi tabel database
│   ├── Observers/          ← otomatisasi (rak terisi otomatis)
│   └── Providers/          ← konfigurasi global aplikasi
├── bootstrap/
│   └── app.php             ← pengaturan inti (middleware, routing didaftarkan)
├── config/                 ← file konfigurasi (database, auth, dll)
├── database/
│   ├── migrations/         ← STRUKTUR tabel (kolom-kolomnya)
│   ├── seeders/            ← data awal (admin, layanan)
│   └── factories/          ← data dummy (jarang dipakai)
├── public/
│   └── index.php           ← pintu masuk semua request
├── resources/
│   └── views/              ← TAMPILAN (file .blade.php)
├── routes/
│   └── web.php             ← daftar URL → controller
├── storage/                ← file sementara (cache, log, session)
├── .env                    ← konfigurasi rahasia (database, dll) — TIDAK di-upload ke GitHub
├── composer.json           ← daftar library yang dipakai
└── README.md               ← cara install
```

### Folder yang paling sering kamu sentuh saat ngoding:
1. `routes/web.php` — daftar URL
2. `app/Http/Controllers/` — logika
3. `app/Models/` — data/tabel
4. `resources/views/` — tampilan
5. `database/migrations/` — struktur tabel

---

## 1.5 File `.env` (Konfigurasi)

File `.env` menyimpan setting yang beda-beda tiap komputer (rahasia). Contoh penting:

```env
APP_NAME="Laundry App"        # nama aplikasi (muncul di judul)
APP_KEY=base64:...            # kunci enkripsi (dibuat otomatis)
DB_DATABASE=laundry_db        # nama database
DB_USERNAME=root              # user MySQL
DB_PASSWORD=                  # password MySQL (kosong di XAMPP)
```

> Kalau ganti `APP_NAME` di `.env`, nama di seluruh web ikut berubah (karena view pakai `config('app.name')`).
> Setelah ubah `.env`, jalankan `php artisan config:clear`.

---

## 1.6 Artisan (Perintah Sakti Laravel)

`artisan` = alat bantu command line Laravel. Yang sering dipakai:

| Perintah | Fungsi |
|----------|--------|
| `php artisan serve` | Menjalankan web di http://localhost:8000 |
| `php artisan migrate` | Membuat tabel dari file migrasi |
| `php artisan migrate:fresh --seed` | Hapus semua tabel, buat ulang, isi data awal |
| `php artisan db:seed` | Jalankan seeder (isi data awal) |
| `php artisan make:controller NamaController` | Membuat file controller baru |
| `php artisan make:model Nama -m` | Membuat model + migrasi sekaligus |
| `php artisan route:list` | Lihat semua route yang ada |
| `php artisan config:clear` | Bersihkan cache config |

> 🎯 Sering ditanya: "Perintah untuk bikin tabel?" → `php artisan migrate`.

---

## ✅ Rangkuman Bab Ini
- Laravel = framework PHP biar ngoding web cepat & rapi.
- **MVC**: Model (data) – View (tampilan) – Controller (logika).
- Alur: Browser → Route → Middleware → Controller → Model → View → Browser.
- Folder penting: `routes/`, `Controllers/`, `Models/`, `views/`, `migrations/`.
- `artisan` = perintah bantu (serve, migrate, dll).

➡️ Lanjut: [02-database-model.md](02-database-model.md)

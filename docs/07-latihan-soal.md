# 7. Latihan + Bank Soal + Jawaban

Latih sampai bisa jawab **tanpa lihat catatan** dan praktek tanpa ragu.

---

## 📝 Bagian A — Soal Konsep (Lisan/Tulis)

**1. Apa itu Laravel dan kenapa dipakai?**
> Framework PHP yang menyediakan struktur & tools siap pakai (routing, database, keamanan, template)
> supaya pembuatan web lebih cepat, rapi, dan aman dibanding ngoding PHP murni.

**2. Jelaskan MVC.**
> Pola pemisahan kode: **Model** (urus data/database), **View** (tampilan ke user),
> **Controller** (logika yang menghubungkan keduanya).

**3. Jelaskan alur dari user buka halaman sampai muncul.**
> Browser → Route (`web.php`) → Middleware (cek login) → Controller → Model (ambil data dari DB)
> → View (jadi HTML) → Response balik ke browser.

**4. Apa beda Migration dan Model?**
> Migration = struktur/blueprint tabel (kolom apa saja). Model = kelas untuk mengakses (ambil/simpan)
> data tabel itu tanpa SQL manual.

**5. Apa fungsi `$fillable` di Model?**
> Daftar kolom yang boleh diisi massal lewat `create()`/`update()`. Kalau kolom tidak ada di situ,
> datanya tidak akan tersimpan (pengaman mass-assignment).

**6. Apa itu Eloquent?**
> ORM Laravel — cara mengakses database menggunakan objek/model PHP, bukan menulis query SQL manual.

**7. Apa fungsi middleware `auth`?**
> Penjaga: memastikan user sudah login sebelum boleh mengakses halaman. Kalau belum, dilempar ke `/login`.

**8. Apa itu `@csrf` dan kenapa wajib?**
> Token keamanan untuk mencegah pemalsuan request (CSRF). Wajib di setiap form POST/PUT/DELETE,
> kalau tidak ada akan error 419.

**9. Apa beda `belongsTo` dan `hasMany`?**
> `belongsTo` = "milik satu" (transaksi milik 1 layanan). `hasMany` = "punya banyak"
> (1 rak punya banyak kolom).

**10. Bagaimana password disimpan? Aman?**
> Disimpan dalam bentuk **hash** (acak satu arah) lewat cast `'password' => 'hashed'`, bukan teks asli.
> Aman karena tidak bisa dibalik.

**11. Apa itu Route Resource?**
> Satu baris `Route::resource()` yang otomatis membuat 7 route CRUD (index, create, store, show,
> edit, update, destroy).

**12. Bagaimana nota dikirim ke WhatsApp tanpa API berbayar?**
> Membuat link `https://wa.me/<nomor>?text=<teks nota>`. Nomor dinormalisasi ke format 62, teks
> di-encode. Klik link → WhatsApp terbuka dengan pesan sudah jadi.

**13. Bagaimana rak bisa terisi otomatis?**
> Lewat **Observer** (`TransaksiObserver`). Saat transaksi dibuat → cari kolom kosong & isi; saat
> status "diambil"/dihapus → kosongkan kolom. Dipicu otomatis oleh event Model.

**14. Bagaimana total harga dihitung?**
> Di controller `store()`/`update()`: `total_harga = harga_satuan × berat`, disimpan ke kolom.

**15. Library apa untuk PDF?**
> `barryvdh/laravel-dompdf`. Dipakai `Pdf::loadView(...)->download(...)`.

---

## 🧪 Bagian B — Soal Praktek (Kerjakan di Project)

> Tiap soal: tulis langkahnya, lalu praktekkan. Cocokkan dengan [06-cara-modifikasi.md](06-cara-modifikasi.md).

1. **Tambah kolom `merk_parfum`** (pilihan saat laundry) di transaksi, tampilkan di nota.
   *(migrasi → fillable → validasi → form → nota)*
2. **Buat No HP wajib diisi** di form transaksi. *(ubah `nullable` → `required`)*
3. **Tampilkan kolom "Tanggal Masuk"** di tabel Data Transaksi kalau belum ada. *(edit view index)*
4. **Tambah menu "Pengeluaran"** lengkap (tabel, controller, route, view, sidebar). *(Skenario 4)*
5. **Tambah status "dibatalkan"** di transaksi. *(ubah enum + validasi + dropdown)*
6. **Tambah diskon** yang mengurangi total. *(kolom + logika di controller + form + nota)*
7. **Ganti nama aplikasi** jadi "Laundry Bersih" lewat `.env`. *(APP_NAME + config:clear)*
8. **Ubah warna tombol** "Tambah" dari hijau ke biru. *(class `btn-success` → `btn-primary`)*
9. **Tambah filter "metode bayar"** di Data Transaksi. *(input di view + `where` di controller index)*
10. **Tampilkan jumlah total karyawan** di Dashboard. *(hitung di controller + kartu di view)*

---

## 🎯 Bagian C — "Tunjuk File" (Penguji suka nanya ini)

Hafalkan **di mana letak** kode untuk hal-hal ini:

| Pertanyaan | Jawaban (file) |
|------------|----------------|
| Di mana daftar semua URL? | `routes/web.php` |
| Di mana logika simpan transaksi? | `app/Http/Controllers/TransaksiController.php` → `store()` |
| Di mana struktur tabel transaksi? | `database/migrations/..._create_transaksis_table.php` |
| Di mana hitung total harga? | `TransaksiController@store/update` |
| Di mana rak otomatis terisi? | `app/Observers/TransaksiObserver.php` |
| Di mana tampilan daftar transaksi? | `resources/views/transaksi/index.blade.php` |
| Di mana sidebar/menu? | `resources/views/layouts/app.blade.php` |
| Di mana data awal (admin, layanan)? | `database/seeders/DatabaseSeeder.php` |
| Di mana logika kirim WhatsApp? | `resources/views/transaksi/nota.blade.php` |
| Di mana filter laporan? | `app/Http/Controllers/LaporanController.php` → `resolveRange()` |
| Di mana koneksi database diatur? | `.env` (dan `config/database.php`) |
| Di mana relasi transaksi → layanan? | `app/Models/Transaksi.php` → `layanan()` |

---

## 🗣️ Bagian D — Simulasi Tanya-Jawab Cepat

**T: "Coba tambahkan field catatan khusus, langsung sekarang."**
> J: "Baik. Saya tambah kolom di migrasi, masukkan ke `$fillable`, tambah validasi di controller,
> tambah input di `_form.blade.php`, lalu `php artisan migrate:fresh --seed`." *(lalu kerjakan)*

**T: "Kalau saya buka /transaksi, apa yang terjadi di belakang layar?"**
> J: Jelaskan 6 langkah alur request (Browser→Route→Middleware→Controller→Model→View).

**T: "Kenapa pakai `with('layanan')`?"**
> J: Untuk mengambil data relasi layanan sekaligus dalam 1 query (menghindari masalah N+1 query
> yang bikin lambat).

**T: "Kalau lupa `@csrf` kenapa?"**
> J: Form POST akan ditolak Laravel dengan error 419 demi keamanan.

**T: "Bedakan `migrate` dan `migrate:fresh`."**
> J: `migrate` menjalankan migrasi yang belum dijalankan (data lama tetap). `migrate:fresh` menghapus
> semua tabel lalu membuat ulang dari awal (data hilang) — biasa ditambah `--seed` untuk isi ulang.

---

## ✅ Checklist Kesiapan Ujian

- [ ] Bisa jelaskan MVC & alur request tanpa contekan
- [ ] Tahu fungsi tiap folder (`routes`, `Controllers`, `Models`, `views`, `migrations`)
- [ ] Bisa tambah kolom baru end-to-end (5 langkah)
- [ ] Bisa tambah menu baru (5 langkah)
- [ ] Bisa ubah validasi & tampilan
- [ ] Hafal perintah artisan penting (`serve`, `migrate`, `make:*`)
- [ ] Tahu letak tiap logika penting (tabel "Tunjuk File")
- [ ] Paham cara baca error & solusi cepatnya

> 🔥 **Strategi terbaik:** kerjakan ulang **Skenario 1 & 4** di [06](06-cara-modifikasi.md) sampai
> lancar di luar kepala. Kalau dua itu lancar, modifikasi apapun bisa kamu turunkan dari situ.

Selamat belajar & semangat ujiannya! 💪

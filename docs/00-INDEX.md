# 📚 Materi Belajar — Sistem Manajemen Laundry (Laravel 11)

Materi ini mengajarkan **dari nol** cara kerja & cara ngoding project ini, supaya kamu paham
betul setiap baris dan siap kalau dites untuk **mengubah-ubah** web-nya langsung.

> Target pembaca: pemula yang belum pernah/baru kenal Laravel.

---

## 🗺️ Urutan Belajar (baca berurutan)

| No | File | Isi |
|----|------|-----|
| 0 | **00-INDEX.md** (file ini) | Peta belajar + istilah dasar |
| 1 | [01-dasar-laravel.md](01-dasar-laravel.md) | Apa itu Laravel, MVC, alur request, struktur folder |
| 2 | [02-database-model.md](02-database-model.md) | Migrasi, tabel, Model, relasi (Eloquent) |
| 3 | [03-routing-controller.md](03-routing-controller.md) | Route, Controller, Middleware, validasi |
| 4 | [04-blade-view.md](04-blade-view.md) | Blade: layout, directive, form |
| 5 | [05-bedah-fitur.md](05-bedah-fitur.md) | Bedah tiap fitur (auth, transaksi, rak, dst) baris per baris |
| 6 | [06-cara-modifikasi.md](06-cara-modifikasi.md) | **Skenario ubah-ubah web (paling penting buat tes praktek)** |
| 7 | [07-latihan-soal.md](07-latihan-soal.md) | Bank soal + jawaban + latihan |

---

## 🧠 Istilah Dasar (hafalkan ini dulu)

| Istilah | Arti gampangnya |
|---------|-----------------|
| **Laravel** | Framework PHP — kerangka siap pakai untuk bikin web cepat & rapi |
| **Framework** | Kumpulan aturan + tools biar gak ngoding dari nol |
| **MVC** | Pola pemisahan kode: **M**odel (data), **V**iew (tampilan), **C**ontroller (otak/logika) |
| **Route** | Daftar URL → menentukan kalau buka alamat X, jalankan fungsi apa |
| **Controller** | Kelas berisi fungsi yang memproses request lalu mengembalikan tampilan |
| **Model** | Kelas yang mewakili 1 tabel database (cara PHP "ngobrol" sama tabel) |
| **Migration** | File untuk membuat/mengubah struktur tabel database lewat kode |
| **Seeder** | File untuk mengisi data awal ke database |
| **Eloquent** | "ORM" Laravel — cara akses database pakai objek PHP, bukan SQL manual |
| **Blade** | Mesin template Laravel untuk nulis HTML + data (`.blade.php`) |
| **Middleware** | "Penjaga pintu" sebelum request masuk ke controller (mis. cek login) |
| **Migrasi** | (sama dengan migration) |
| **Request** | Permintaan dari browser (buka halaman / submit form) |
| **Response** | Jawaban dari server (biasanya halaman HTML) |

---

## ⚙️ Teknologi yang Dipakai di Project Ini

- **Laravel 11** (PHP 8.2+) — framework utama
- **MySQL** — database (via XAMPP)
- **Blade** — template tampilan
- **Bootstrap 5** (lewat CDN) — styling/CSS biar rapi tanpa ngoding CSS banyak
- **barryvdh/laravel-dompdf** — bikin laporan PDF
- **WhatsApp `wa.me`** — kirim nota (cuma link, gratis)

---

## 🔑 Hal Penting yang Sering Ditanya Penguji

1. "Coba jelaskan alur dari klik tombol sampai data tersimpan." → baca [01](01-dasar-laravel.md) bagian *Alur Request*.
2. "Tambahkan kolom baru di tabel X dan tampilkan." → baca [06](06-cara-modifikasi.md) Skenario 1.
3. "Tambahkan menu baru." → baca [06](06-cara-modifikasi.md) Skenario 4.
4. "Di mana logika hitung total / estimasi / rak otomatis?" → baca [05](05-bedah-fitur.md).
5. "Apa itu MVC / Eloquent / migration?" → tabel istilah di atas + [01](01-dasar-laravel.md).

> 💡 **Tips ujian:** kuasai dulu **alur 1 fitur secara utuh** (Transaksi paling lengkap),
> karena fitur lain polanya sama persis. Kalau paham 1, paham semua.

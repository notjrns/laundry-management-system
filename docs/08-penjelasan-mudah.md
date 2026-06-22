# 8. Penjelasan Versi Gampang (Buat Orang Awam) 🌱

> Khusus buat kamu yang **bukan anak IT**. Di sini gak ada istilah ribet. Pakai perumpamaan
> sehari-hari + ada **kalimat siap pakai** yang bisa langsung kamu omongin pas dites/presentasi.

---

## 🧺 Aplikasi ini sebenarnya apa? (1 kalimat)

> **"Ini aplikasi kasir & pencatatan untuk laundry — buat catat pesanan pelanggan, hitung
> harga otomatis, atur rak penyimpanan, data karyawan, dan bikin laporan pendapatan."**

Gampangnya: ini **buku catatan laundry yang pintar**, versi digital, biar gak nyatet di kertas lagi.

---

## 🏢 Cara kerjanya gimana? (Bayangkan sebuah KANTOR)

Bayangin aplikasi ini seperti **kantor laundry** dengan beberapa "pegawai" yang punya tugas masing-masing.
Pas kamu klik sesuatu di web, di belakang layar terjadi ini:

```
Kamu (klik menu) 
   │
   ▼
🛎️ RESEPSIONIS  → nerima permintaan, arahin ke pegawai yang tepat
   │
   ▼
👨‍💼 PEGAWAI     → ngerjain tugasnya (ambil data / hitung / simpan)
   │        │
   ▼        ▼
🗄️ GUDANG   📋 PAPAN TAMPILAN → hasilnya dipajang buat kamu lihat
ARSIP
(data)
```

| "Pegawai" | Tugasnya | Nama teknisnya (buat jaga-jaga ditanya) |
|-----------|----------|------------------------------------------|
| 🛎️ **Resepsionis** | Nerima klik & arahkan | *Route* |
| 👨‍💼 **Pegawai** | Ngerjain logika (hitung, simpan) | *Controller* |
| 🗄️ **Gudang arsip** | Tempat semua data disimpan | *Database / Model* |
| 📋 **Papan tampilan** | Yang kamu lihat di layar | *View* |
| 💂 **Satpam** | Cek kamu udah login belum | *Middleware* |

**Contoh cerita lengkap** (kalau ditanya "alurnya gimana?"):
> "Pas saya klik menu Data Transaksi, **resepsionis** nangkep permintaan itu, lalu nyuruh
> **pegawai** bagian transaksi. Pegawai itu ambil data dari **gudang arsip**, terus dipajang di
> **papan tampilan** jadi tabel yang saya lihat. Sebelum masuk, **satpam** ngecek saya udah login."

> 💡 Cukup hafal cerita ini. Ganti "Data Transaksi" sesuai yang ditanya — polanya selalu sama.

---

## 🚪 Keliling Tiap Menu (pakai bahasa manusia)

| Menu | Fungsinya (bahasa awam) | Kalau ditanya, bilang... |
|------|--------------------------|--------------------------|
| **Home** | Halaman pembuka, ada tombol Login & Daftar | "Ini pintu depan aplikasi." |
| **Login / Register** | Masuk pakai akun / bikin akun admin baru | "Cuma admin yang bisa masuk, biar aman." |
| **Dashboard** | Ringkasan: hari ini ada berapa order, pemasukan berapa | "Ini papan info ringkas, lihat sekilas kondisi laundry hari ini." |
| **Tambah Transaksi** | Catat order baru + data pelanggan, harga ngitung sendiri | "Di sini saya input order. Total otomatis kehitung dari berat × harga." |
| **Data Transaksi** | Daftar semua order, bisa dicari/edit/hapus | "Ini buku besar semua transaksi." |
| **Rak** | Petak penyimpanan; order otomatis masuk & keluar rak | "Order otomatis nempel ke rak kosong, dan keluar sendiri pas diambil." |
| **Atur Layanan** | Atur daftar paket + harganya (Cuci Gosok dll) | "Di sini saya atur paket & harga, bisa nambah/ubah sendiri." |
| **Data Karyawan** | Catat data pegawai laundry | "Ini daftar karyawan." |
| **Laporan** | Rekap pemasukan per hari/minggu/bulan + simpan PDF | "Di sini saya tarik laporan dan export ke PDF." |

---

## 🎤 Kalau Penguji Nanya, Jawab Begini (Bahasa Simpel)

**"Aplikasi ini dibuat pakai apa?"**
> "Pakai **Laravel** — itu kerangka siap pakai untuk bikin website pakai bahasa PHP. Datanya
> disimpan di **MySQL**. Tampilannya dirapihin pakai **Bootstrap**."

**"Kenapa pakai Laravel, gak bikin dari nol?"**
> "Biar cepat dan rapi. Laravel udah nyediain banyak hal jadi (login, koneksi database, keamanan),
> jadi gak perlu bikin semuanya dari awal."

**"Datanya disimpan di mana?"**
> "Di database MySQL, ada beberapa tabel: tabel transaksi, layanan, karyawan, rak, dan akun admin."

**"Harga totalnya ngitung gimana?"**
> "Otomatis: **berat dikali harga per kilo** paket yang dipilih. Jadi admin gak ngitung manual."

**"Kok rak bisa keisi sendiri?"**
> "Ada 'asisten otomatis' di sistem. Begitu ada order baru, dia langsung naruh ke kotak rak yang
> kosong. Pas order udah diambil pelanggan, kotaknya otomatis dikosongin lagi."

**"Nota ke WhatsApp gimana caranya?"**
> "Pas selesai input, ada tombol kirim WhatsApp. Diklik, langsung kebuka WhatsApp dengan teks nota
> udah jadi, tinggal kirim ke pelanggan. Gratis, gak pakai biaya."

**"Yang bisa pakai aplikasi ini siapa?"**
> "Cuma admin yang sudah login. Kalau belum login, otomatis disuruh login dulu."

---

## 🛠️ Kalau Disuruh "Ubah Ini-Itu" — Resep Paling Gampang

Rahasianya: **hampir semua perubahan itu cuma buka beberapa "laci" yang sama.** Anggap aplikasi ini
punya **5 laci**. Kalau disuruh nambah data baru (misal "tambah kolom email pelanggan"), buka 5 laci
ini **berurutan**:

| Urutan | "Laci" | Tugasnya | Nama filenya |
|--------|--------|----------|--------------|
| 1️⃣ | **Cetakan kotak data** | Bikin tempat baru di gudang | file di folder `database/migrations` |
| 2️⃣ | **Daftar izin** | Kasih izin kolom itu boleh diisi | file di folder `app/Models` |
| 3️⃣ | **Satpam pemeriksa** | Cek isian bener gak | file di folder `app/Http/Controllers` |
| 4️⃣ | **Formulir** | Tempat ngetik datanya | file form di `resources/views` |
| 5️⃣ | **Papan tampilan** | Tempat datanya muncul | file tampilan di `resources/views` |

> Hafal jembatan keledai: **"Kotak → Izin → Satpam → Formulir → Papan"**.

Kalau perubahannya **cuma soal tampilan** (ganti warna tombol, ganti tulisan, ubah urutan, sembunyiin
kolom) → **cukup buka laci nomor 5 (Papan tampilan) aja**, gak usah yang lain.

> 📌 Langkah teknis detail tiap laci ada di [06-cara-modifikasi.md](06-cara-modifikasi.md). Bagian ini
> cuma biar kamu paham "gambaran besarnya" dulu, baru ke detail.

### Contoh ngomong pas disuruh ubah:
> Penguji: *"Tambahkan kolom email pelanggan."*
> Kamu: *"Baik. Saya buka 5 tempat: pertama bikin kolomnya di cetakan tabel, kasih izin di model,
> tambah pengecekan di controller, tambah kotak isian di formulir, lalu tampilkan di halaman."*
> (lalu buka [06](06-cara-modifikasi.md) Skenario 1, ikuti langkahnya)

---

## 🗣️ Kalimat Siap Pakai Buat Presentasi (tinggal baca)

1. *"Aplikasi kami adalah sistem manajemen laundry berbasis web, dibuat dengan Laravel dan MySQL."*
2. *"Aksesnya khusus admin, jadi harus login dulu sebelum bisa dipakai."*
3. *"Fitur utamanya: input transaksi dengan harga otomatis, pengaturan rak yang otomatis terisi,
   manajemen layanan dan karyawan, serta laporan yang bisa diekspor ke PDF."*
4. *"Setiap transaksi bisa langsung dikirim notanya ke WhatsApp pelanggan."*
5. *"Strukturnya rapi memakai pola MVC — pemisahan antara data, tampilan, dan logika — jadi gampang
   dikembangkan."*
6. *"Harga total dihitung otomatis dari berat dikali tarif paket, jadi mengurangi kesalahan hitung."*

---

## ✅ Yang Penting Diingat Aja (kalau panik, ingat ini)

1. Aplikasi = **kantor**: Resepsionis (route) → Pegawai (controller) → Gudang (database) → Papan (tampilan).
2. **5 laci** buat ngubah data: **Kotak → Izin → Satpam → Formulir → Papan**.
3. Ubah **tampilan doang** = cukup buka file di `resources/views`.
4. Harga total = **berat × harga paket** (otomatis).
5. Rak = **otomatis** (ada asisten yang ngatur).
6. Semua cuma bisa diakses setelah **login**.

> 🎯 Gak perlu hafal kode. Cukup paham **cerita & perumpamaannya**, lalu pas praktek buka panduan
> langkah di [06-cara-modifikasi.md](06-cara-modifikasi.md). Kamu pasti bisa! 💪

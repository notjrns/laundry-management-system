# 2. Database — Migrasi, Tabel, Model, Relasi

## 2.1 Konsep: Migration vs Model

- **Migration** = blueprint/struktur tabel (kolom apa saja, tipe datanya apa). Ditulis pakai PHP, lalu
  `php artisan migrate` mengeksekusinya jadi tabel asli di MySQL.
- **Model** = kelas PHP yang mewakili 1 tabel, dipakai untuk **ambil/simpan data** tanpa nulis SQL.

> Aturan penamaan Laravel: Model `Transaksi` ↔ tabel `transaksis` (jamak, huruf kecil).
> Model `Karyawan` ↔ tabel `karyawans`. Otomatis dijodohkan oleh Laravel.

---

## 2.2 Daftar Tabel di Project Ini (7 tabel)

| Tabel | Fungsi | Model |
|-------|--------|-------|
| `users` | Akun admin (login) | `User` |
| `layanans` | Paket layanan (Cuci Gosok, dll) + harga | `Layanan` |
| `karyawans` | Data karyawan | `Karyawan` |
| `transaksis` | Transaksi laundry pelanggan | `Transaksi` |
| `raks` | Rak penyimpanan | `Rak` |
| `rak_koloms` | Kolom-kolom di dalam rak | `RakKolom` |
| `password_reset_tokens` | Bawaan Laravel (reset password) | — |

---

## 2.3 Membaca File Migrasi

Contoh: [database/migrations/2024_01_01_000001_create_layanans_table.php](../database/migrations/2024_01_01_000001_create_layanans_table.php)

```php
public function up(): void
{
    Schema::create('layanans', function (Blueprint $table) {
        $table->id();                                              // kolom id (auto increment)
        $table->string('nama');                                    // VARCHAR — teks pendek
        $table->enum('satuan', ['kg', 'pcs'])->default('kg');      // pilihan terbatas
        $table->unsignedInteger('estimasi_nilai')->default(1);     // angka positif
        $table->enum('estimasi_satuan', ['jam', 'hari'])->default('hari');
        $table->unsignedInteger('harga');                          // angka (Rupiah)
        $table->timestamps();                                      // created_at & updated_at otomatis
    });
}

public function down(): void
{
    Schema::dropIfExists('layanans');   // kebalikan up() — hapus tabel
}
```

### Tipe kolom yang sering dipakai:
| Kode | Hasil | Untuk apa |
|------|-------|-----------|
| `$table->id()` | BIGINT auto increment | ID utama |
| `$table->string('x')` | VARCHAR(255) | teks pendek (nama, email) |
| `$table->text('x')` | TEXT | teks panjang (alamat, catatan) |
| `$table->integer('x')` / `unsignedInteger` | INT | angka |
| `$table->decimal('x', 8, 2)` | DECIMAL | angka desimal (berat: 8 digit, 2 di belakang koma) |
| `$table->enum('x', ['a','b'])` | ENUM | pilihan terbatas |
| `$table->date('x')` / `dateTime('x')` | tanggal / tanggal+jam | |
| `$table->boolean('x')` | true/false | status ya/tidak |
| `->nullable()` | boleh kosong | |
| `->default(nilai)` | nilai default | |
| `$table->timestamps()` | created_at + updated_at | otomatis terisi |

---

## 2.4 Relasi Antar Tabel (FOREIGN KEY)

Lihat [migrasi transaksis](../database/migrations/2024_01_01_000003_create_transaksis_table.php):

```php
$table->foreignId('layanan_id')->constrained('layanans')->restrictOnDelete();
```

Artinya:
- `layanan_id` adalah **foreign key** → nyambung ke `id` di tabel `layanans`.
- `restrictOnDelete()` = layanan **tidak boleh dihapus** kalau masih dipakai transaksi.

Relasi di project ini:

```
layanans  1 ────── ∞  transaksis      (1 layanan dipakai banyak transaksi)
raks      1 ────── ∞  rak_koloms      (1 rak punya banyak kolom)
transaksis 1 ───── ∞  rak_koloms      (1 transaksi menempati kolom rak)
```

`onDelete` jenis lain:
- `cascadeOnDelete()` = kalau induk dihapus, anak ikut terhapus (dipakai rak → kolom).
- `nullOnDelete()` = kalau induk dihapus, FK di anak jadi NULL (dipakai transaksi → kolom).

---

## 2.5 Model & `$fillable`

Contoh [app/Models/Layanan.php](../app/Models/Layanan.php):

```php
class Layanan extends Model
{
    protected $fillable = [
        'nama', 'satuan', 'estimasi_nilai', 'estimasi_satuan', 'harga',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
```

- **`$fillable`** = daftar kolom yang boleh diisi massal (lewat `create()`/`update()`).
  Ini pengaman: kalau kolom tidak ada di sini, datanya **tidak akan tersimpan**.
  > 🎯 Bug umum: "data gak kesimpan" → sering karena kolom lupa ditambah di `$fillable`.

---

## 2.6 `$casts` (Mengubah Tipe Data Otomatis)

Lihat [app/Models/Transaksi.php](../app/Models/Transaksi.php):

```php
protected function casts(): array
{
    return [
        'berat' => 'decimal:2',
        'tanggal_masuk' => 'date',
        'estimasi_selesai' => 'datetime',
    ];
}
```

`casts` mengubah data dari database jadi tipe yang enak dipakai. Karena `tanggal_masuk` di-cast
jadi `date`, kita bisa langsung `$transaksi->tanggal_masuk->format('d/m/Y')` (objek Carbon/tanggal).

---

## 2.7 Relasi di Model (Eloquent Relationships)

Ada 2 arah relasi yang dipakai:

**`belongsTo`** (milik) — di [Transaksi.php](../app/Models/Transaksi.php):
```php
public function layanan(): BelongsTo
{
    return $this->belongsTo(Layanan::class);
}
```
Artinya: 1 transaksi **milik** 1 layanan. Cara pakai: `$transaksi->layanan->nama`.

**`hasMany`** (punya banyak) — di [Rak.php](../app/Models/Rak.php):
```php
public function koloms(): HasMany
{
    return $this->hasMany(RakKolom::class)->orderBy('nomor_kolom');
}
```
Artinya: 1 rak **punya banyak** kolom. Cara pakai: `$rak->koloms` (kumpulan kolom).

---

## 2.8 Eloquent — Cara Akses Data (PENTING)

Tanpa SQL manual. Contoh perintah yang dipakai di project:

```php
// Ambil semua
Transaksi::all();

// Ambil + relasi (biar gak lambat / N+1), urut terbaru, dibagi halaman
Transaksi::with('layanan')->latest()->paginate(10);

// Cari 1 berdasarkan id (error kalau tidak ada)
Layanan::findOrFail($id);

// Filter
Transaksi::where('status', 'diproses')->count();
Transaksi::whereDate('tanggal_masuk', today())->sum('total_harga');

// Buat data baru
Transaksi::create([...]);

// Update
$transaksi->update([...]);

// Hapus
$transaksi->delete();

// Cari atau buat kalau belum ada
Layanan::updateOrCreate(['nama' => 'X'], ['harga' => 5000]);
```

| Method | Arti |
|--------|------|
| `all()` | semua baris |
| `find($id)` / `findOrFail($id)` | cari per id |
| `where('kolom', 'nilai')` | filter |
| `with('relasi')` | sekalian ambil data relasi |
| `latest()` | urut terbaru (by created_at) |
| `paginate(10)` | bagi jadi halaman @10 |
| `create([...])` | buat baru |
| `update([...])` | ubah |
| `delete()` | hapus |
| `count()` / `sum('x')` | hitung jumlah / total |

---

## 2.9 Seeder (Data Awal)

[database/seeders/DatabaseSeeder.php](../database/seeders/DatabaseSeeder.php) mengisi:
- 1 akun admin (`admin@laundry.test` / `password`)
- 16 paket layanan
- 1 contoh karyawan + 1 rak (20 kolom)

Dijalankan dengan `php artisan db:seed` atau ikut `php artisan migrate:fresh --seed`.

```php
User::updateOrCreate(
    ['email' => 'admin@laundry.test'],
    ['name' => 'Admin Laundry', 'password' => Hash::make('password')]
);
```
`Hash::make()` = enkripsi password (tidak disimpan sebagai teks asli — demi keamanan).

---

## ✅ Rangkuman Bab Ini
- Migrasi = struktur tabel; Model = cara akses tabel.
- `$fillable` = kolom yang boleh diisi (sering jadi sumber bug kalau lupa).
- Relasi: `belongsTo` (milik) & `hasMany` (punya banyak).
- Eloquent: `create / update / delete / where / with / paginate`.
- Seeder = data awal; `Hash::make()` untuk password.

➡️ Lanjut: [03-routing-controller.md](03-routing-controller.md)

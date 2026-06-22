# 5. Bedah Fitur (Lengkap, Baris per Baris)

Tiap fitur dijelaskan dengan pola: **Route → Controller → Model → View**.
Kuasai **Transaksi** dulu (paling lengkap), fitur lain polanya sama.

---

## 5.1 Auth (Register, Login, Logout)

**File:** [AuthController.php](../app/Http/Controllers/AuthController.php), view di `resources/views/auth/`.

**Route** (di grup `guest` & `auth`):
```php
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

**Register** (`register()`):
```php
$data = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users,email'],   // email tak boleh kembar
    'password' => ['required', 'confirmed', Password::min(6)], // 'confirmed' = harus sama password_confirmation
]);
$user = User::create($data);   // password otomatis di-hash (lihat cast 'hashed' di User)
Auth::login($user);            // langsung login
return redirect()->route('dashboard');
```

**Login** (`login()`):
```php
$credentials = $request->validate([
    'email' => ['required', 'email'],
    'password' => ['required'],
]);
if (Auth::attempt($credentials, $request->boolean('remember'))) { // cek email+password
    $request->session()->regenerate();
    return redirect()->intended(route('dashboard'));
}
return back()->withErrors(['email' => 'Email atau password salah.']);
```
- `Auth::attempt()` = cek kecocokan ke tabel users. Return true kalau benar.

**Logout** (`logout()`): `Auth::logout()` + hapus session → balik ke home.

> Konsep penting: **password tidak pernah disimpan asli**. Disimpan ter-hash (cast `'password' => 'hashed'`
> di [User.php](../app/Models/User.php)). Saat login, Laravel membandingkan hash.

---

## 5.2 Dashboard (Statistik)

**File:** [DashboardController.php](../app/Http/Controllers/DashboardController.php) → view [dashboard.blade.php](../resources/views/dashboard.blade.php).

```php
public function index()
{
    $hariIni = Carbon::today();
    $stats = [
        'transaksi_hari_ini'   => Transaksi::whereDate('tanggal_masuk', $hariIni)->count(),
        'diproses'             => Transaksi::where('status', 'diproses')->count(),
        'selesai'              => Transaksi::where('status', 'selesai')->count(),
        'karyawan'             => Karyawan::where('status', 'aktif')->count(),
        'pendapatan_hari_ini'  => (int) Transaksi::whereDate('tanggal_masuk', $hariIni)->sum('total_harga'),
        'pendapatan_bulan_ini' => (int) Transaksi::whereMonth('tanggal_masuk', $hariIni->month)
                                    ->whereYear('tanggal_masuk', $hariIni->year)->sum('total_harga'),
    ];
    $terbaru = Transaksi::with('layanan')->latest()->take(8)->get();
    return view('dashboard', compact('stats', 'terbaru'));
}
```
Inti: kumpulkan angka pakai `count()` & `sum()`, kirim ke view sebagai array `$stats`. View tinggal
menampilkan `{{ $stats['diproses'] }}` di dalam kartu.

---

## 5.3 Transaksi (FITUR PALING LENGKAP — pahami betul)

**File:** [TransaksiController.php](../app/Http/Controllers/TransaksiController.php), view di `resources/views/transaksi/`.

### a) Daftar + Pencarian + Filter — `index()`
```php
$query = Transaksi::with('layanan')->latest();
if ($cari = $request->input('cari')) {                 // kalau ada kata kunci
    $query->where(function ($q) use ($cari) {
        $q->where('nama_pelanggan', 'like', "%{$cari}%")
          ->orWhere('kode', 'like', "%{$cari}%")
          ->orWhere('no_hp', 'like', "%{$cari}%");
    });
}
if ($status = $request->input('status')) {             // filter status
    $query->where('status', $status);
}
$transaksis = $query->paginate(10)->withQueryString(); // bagi halaman + jaga query saat ganti halaman
```
- `like "%kata%"` = cari yang mengandung kata.
- `withQueryString()` = saat klik halaman 2, filter pencarian tetap terbawa.

### b) Form Tambah — `create()`
```php
$layanans = Layanan::orderBy('nama')->get(); // untuk dropdown pilihan layanan
return view('transaksi.create', compact('layanans'));
```

### c) Simpan — `store()` (logika bisnis penting di sini)
```php
$data = $this->validateData($request);
$layanan = Layanan::findOrFail($data['layanan_id']);
$data['harga_satuan'] = $layanan->harga;                              // simpan harga saat itu
$data['total_harga']  = (int) round($layanan->harga * (float) $data['berat']); // TOTAL = harga × berat
$data['kode']         = $this->generateKode();                        // bikin nomor nota unik
if (empty($data['estimasi_selesai'])) {                               // ESTIMASI otomatis
    $data['estimasi_selesai'] = $this->hitungEstimasi($layanan);
}
$transaksi = Transaksi::create($data);
return redirect()->route('transaksi.nota', $transaksi);               // ke halaman nota
```

**Hitung total:** `total_harga = harga_satuan × berat`. Disimpan ke DB (bukan dihitung ulang terus).

**Generate kode nota** (`generateKode()`):
```php
$tanggal = Carbon::today()->format('Ymd');                            // 20260621
$jumlahHariIni = Transaksi::whereDate('created_at', today())->count() + 1;
return sprintf('TRX-%s-%04d', $tanggal, $jumlahHariIni);              // TRX-20260621-0001
```

**Estimasi otomatis** (`hitungEstimasi()`):
```php
return $layanan->estimasi_satuan === 'jam'
    ? Carbon::now()->addHours($layanan->estimasi_nilai)   // mis. +8 jam
    : Carbon::now()->addDays($layanan->estimasi_nilai);   // mis. +3 hari
```

### d) Edit & Update
`edit()` tampilkan form berisi data lama; `update()` hitung ulang total lalu `$transaksi->update($data)`.

### e) Hapus — `destroy()`
`$transaksi->delete();` lalu redirect.

### f) Nota + Kirim WhatsApp — `nota()` + [nota.blade.php](../resources/views/transaksi/nota.blade.php)
Logika WhatsApp ada di **view** (Blade), bukan controller:
```php
// Normalisasi nomor: 0812... → 62812...
$hp = preg_replace('/[^0-9]/', '', $transaksi->no_hp);
if (str_starts_with($hp, '0')) $hp = '62' . substr($hp, 1);
// Susun teks nota lalu encode jadi URL
$waUrl = 'https://wa.me/' . $hp . '?text=' . rawurlencode($pesan);
```
Tombol: `<a href="{{ $waUrl }}" target="_blank">Kirim via WhatsApp</a>`. Klik → buka WhatsApp
dengan teks nota sudah jadi. **Gratis, tanpa API.**

### g) Total otomatis di layar (JavaScript) — di `_form.blade.php`
Saat user pilih layanan / ketik berat, total langsung muncul tanpa reload, pakai JS kecil yang
membaca `data-harga` dari `<option>`. Estimasi juga ikut terisi otomatis.

---

## 5.4 Rak + OTOMATISASI (Observer) — Konsep Paling "Wow"

**Konsep:** transaksi **otomatis** masuk/keluar rak berdasarkan status. Tidak ada input manual.

**File kunci:** [TransaksiObserver.php](../app/Observers/TransaksiObserver.php).

**Apa itu Observer?** "Mata-mata" yang otomatis jalan tiap ada kejadian pada sebuah Model
(dibuat/diubah/dihapus). Didaftarkan di [Transaksi.php](../app/Models/Transaksi.php):
```php
#[ObservedBy(TransaksiObserver::class)]
class Transaksi extends Model { ... }
```

Isi Observer:
```php
public function created(Transaksi $t) {           // saat transaksi BARU dibuat
    if ($t->status !== 'diambil') $this->tempatkanKeRak($t);  // taruh ke kolom kosong
}
public function updated(Transaksi $t) {           // saat transaksi DIUBAH
    if ($t->status === 'diambil') { $this->keluarkanDariRak($t); return; } // status diambil → keluar
    $kolom = $this->kolomMilik($t);
    $kolom ? $this->isiKolom($kolom, $t) : $this->tempatkanKeRak($t);       // sinkronkan
}
public function deleted(Transaksi $t) {           // saat transaksi DIHAPUS
    $this->keluarkanDariRak($t);                  // kosongkan kolomnya
}
```

`tempatkanKeRak()` = cari kolom kosong pertama lalu isi:
```php
$kolom = RakKolom::where('terisi', false)->orderBy('rak_id')->orderBy('nomor_kolom')->first();
if ($kolom) $this->isiKolom($kolom, $transaksi);
```

**Alur lengkap:**
1. Admin buat transaksi → Observer `created` → kolom rak kosong pertama otomatis terisi.
2. Admin ubah status jadi "diambil" → Observer `updated` → kolom dikosongkan.
3. Admin hapus transaksi → Observer `deleted` → kolom dikosongkan.

**Rak (CRUD biasa)** di [RakController.php](../app/Http/Controllers/RakController.php):
- Buat rak → otomatis dibuatkan kolom sebanyak `jumlah_kolom` lewat `$rak->generateKolom()`
  (lihat [Rak.php](../app/Models/Rak.php)).
- Halaman [rak/show.blade.php](../resources/views/rak/show.blade.php) hanya **menampilkan** kondisi
  (tidak ada tombol isi manual lagi).

---

## 5.5 Atur Layanan (CRUD Paket)

**File:** [LayananController.php](../app/Http/Controllers/LayananController.php), view `resources/views/layanan/`.

CRUD standar. Yang menarik: saat hapus, dicek dulu apakah dipakai transaksi:
```php
public function destroy(Layanan $layanan)
{
    if ($layanan->transaksis()->exists()) {   // kalau masih dipakai
        return back()->with('error', 'Layanan tidak bisa dihapus karena sudah dipakai di transaksi.');
    }
    $layanan->delete();
    ...
}
```
Route-nya: `Route::resource('layanan', LayananController::class)->except('show');` (tanpa halaman detail).

---

## 5.6 Data Karyawan (CRUD Sederhana)

**File:** [KaryawanController.php](../app/Http/Controllers/KaryawanController.php).
Ini contoh CRUD **paling sederhana** (tanpa logika khusus) — bagus untuk dipelajari sebagai pola dasar:
`index` (list + cari), `create`/`store` (tambah), `edit`/`update` (ubah), `destroy` (hapus).

> Catatan: tabel `karyawans` hanya **data**, tidak punya akun login. Akun login ada di tabel `users`.

---

## 5.7 Laporan + Export PDF

**File:** [LaporanController.php](../app/Http/Controllers/LaporanController.php).

### Filter periode — `resolveRange()`
```php
return match ($periode) {
    'minggu_ini' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek(), '...'],
    'bulan_ini'  => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), '...'],
    'custom'     => $this->customRange($request),         // pakai tgl_dari & tgl_sampai
    default      => [Carbon::today(), Carbon::today()->endOfDay(), '...'], // hari ini
};
```
`match` = seperti switch — pilih rentang tanggal sesuai pilihan user.

### Ambil data sesuai rentang — `index()`
```php
$transaksis = Transaksi::with('layanan')
    ->whereBetween('tanggal_masuk', [$dari, $sampai])
    ->orderBy('tanggal_masuk')->get();
$ringkasan = ['jumlah' => $transaksis->count(), 'total' => $transaksis->sum('total_harga'), ...];
```

### Export PDF — `pdf()`
```php
$pdf = Pdf::loadView('laporan.pdf', compact('transaksis', 'ringkasan', 'label', ...))
    ->setPaper('a4', 'portrait');
return $pdf->download('laporan-laundry-....pdf');
```
- `Pdf::loadView()` = render view [laporan/pdf.blade.php](../resources/views/laporan/pdf.blade.php) jadi PDF.
- `download()` = paksa browser mengunduh file.
- Library: `barryvdh/laravel-dompdf` (didaftarkan di `composer.json`).

---

## ✅ Rangkuman Bab Ini
- Semua fitur ikut pola Route → Controller → Model → View.
- **Transaksi**: total = harga×berat, kode nota auto, estimasi auto, nota via wa.me.
- **Rak**: otomatis lewat **Observer** (created/updated/deleted).
- **Layanan**: CRUD, tak bisa hapus kalau dipakai.
- **Karyawan**: CRUD paling polos (pola dasar).
- **Laporan**: filter tanggal (`match` + `whereBetween`) + PDF (dompdf).

➡️ Lanjut: [06-cara-modifikasi.md](06-cara-modifikasi.md)

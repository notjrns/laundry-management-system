# 3. Routing, Controller, Middleware, Validasi

## 3.1 Routing — Daftar URL

Semua URL didaftarkan di [routes/web.php](../routes/web.php). Bentuk dasar:

```php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

Artinya:
- `get` = method HTTP (buka halaman). Ada juga `post` (submit form), `put` (update), `delete` (hapus).
- `/dashboard` = URL-nya.
- `[DashboardController::class, 'index']` = jalankan fungsi `index()` di `DashboardController`.
- `->name('dashboard')` = kasih **nama** route, biar bisa dipanggil `route('dashboard')` di view.

> 🎯 Kenapa pakai nama route? Supaya kalau URL berubah, link di view gak rusak. Di Blade:
> `<a href="{{ route('dashboard') }}">` — ini otomatis jadi `/dashboard`.

---

## 3.2 Route Group & Middleware

```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    ...
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    ...
});
```

- **`middleware('guest')`** = grup ini hanya untuk yang **belum login** (login & register).
- **`middleware('auth')`** = grup ini hanya untuk yang **sudah login** (semua menu admin).

**Middleware = penjaga pintu.** `auth` mengecek login sebelum request masuk controller.
Kalau belum login dan buka `/dashboard` → otomatis dilempar ke `/login`.

> Pengaturan lempar-melempar ini ada di [bootstrap/app.php](../bootstrap/app.php):
> ```php
> $middleware->redirectGuestsTo(fn () => route('login'));
> $middleware->redirectUsersTo(fn () => route('dashboard'));
> ```

---

## 3.3 Route Resource (Hemat Nulis)

Daripada nulis 7 route CRUD satu-satu, Laravel sediakan:

```php
Route::resource('transaksi', TransaksiController::class);
```

Satu baris ini otomatis membuat 7 route sekaligus:

| Method | URL | Fungsi Controller | Nama Route | Gunanya |
|--------|-----|-------------------|------------|---------|
| GET | `/transaksi` | `index()` | `transaksi.index` | daftar |
| GET | `/transaksi/create` | `create()` | `transaksi.create` | form tambah |
| POST | `/transaksi` | `store()` | `transaksi.store` | simpan baru |
| GET | `/transaksi/{id}` | `show()` | `transaksi.show` | detail |
| GET | `/transaksi/{id}/edit` | `edit()` | `transaksi.edit` | form edit |
| PUT/PATCH | `/transaksi/{id}` | `update()` | `transaksi.update` | simpan perubahan |
| DELETE | `/transaksi/{id}` | `destroy()` | `transaksi.destroy` | hapus |

> Variasi di project:
> - `Route::resource('layanan', ...)->except('show');` → semua KECUALI show.
> - Route tambahan manual: `Route::get('transaksi/{transaksi}/nota', ...)` untuk halaman nota.

Cek semua route: `php artisan route:list`.

---

## 3.4 Controller — Otak Aplikasi

Contoh kerangka [TransaksiController](../app/Http/Controllers/TransaksiController.php):

```php
class TransaksiController extends Controller
{
    public function index()   { /* tampilkan daftar */ }
    public function create()  { /* tampilkan form tambah */ }
    public function store()   { /* simpan data baru */ }
    public function show()    { /* tampilkan 1 data */ }
    public function edit()    { /* tampilkan form edit */ }
    public function update()  { /* simpan perubahan */ }
    public function destroy() { /* hapus */ }
}
```

7 fungsi ini = pasangan dari 7 route resource. Pola **CRUD** (Create, Read, Update, Delete) ini
**sama di semua controller** (Karyawan, Layanan, Rak). Kuasai 1, paham semua.

### Contoh `store()` (paling penting dipahami):
```php
public function store(Request $request)
{
    $data = $this->validateData($request);              // 1. validasi input
    $layanan = Layanan::findOrFail($data['layanan_id']); // 2. ambil layanan
    $data['total_harga'] = $layanan->harga * $data['berat']; // 3. hitung total
    $transaksi = Transaksi::create($data);              // 4. simpan ke database
    return redirect()->route('transaksi.nota', $transaksi); // 5. pindah halaman
}
```

`Request $request` = objek berisi semua data yang dikirim dari form.
`$request->input('nama')` atau `$request->nama` = ambil 1 field.

---

## 3.5 Route Model Binding (Otomatis Ambil Data)

Perhatikan parameter fungsi:
```php
public function show(Transaksi $transaksi) { ... }
```
URL `/transaksi/5` → Laravel **otomatis** cari `Transaksi` dengan id 5 dan masukkan ke `$transaksi`.
Kita tidak perlu nulis `Transaksi::find(5)`. Kalau id tidak ada → otomatis error 404.

> Syarat: nama parameter (`$transaksi`) harus cocok dengan nama di route (`{transaksi}`).

---

## 3.6 Validasi Input (Keamanan & Kebenaran Data)

Sebelum simpan, data dicek dulu. Contoh dari TransaksiController:

```php
$request->validate([
    'nama_pelanggan' => ['required', 'string', 'max:255'],
    'no_hp'          => ['nullable', 'string', 'max:20'],
    'layanan_id'     => ['required', 'exists:layanans,id'],
    'berat'          => ['required', 'numeric', 'min:0.1'],
    'status'         => ['required', 'in:diproses,selesai,diambil'],
]);
```

| Aturan | Arti |
|--------|------|
| `required` | wajib diisi |
| `nullable` | boleh kosong |
| `string` / `numeric` / `integer` / `date` | tipe data |
| `max:255` / `min:0.1` | batas maksimal/minimal |
| `email` | harus format email |
| `unique:users,email` | tidak boleh kembar di tabel users kolom email |
| `exists:layanans,id` | harus ada di tabel layanans |
| `in:a,b,c` | harus salah satu dari pilihan |
| `confirmed` | harus sama dengan field `x_confirmation` (dipakai password) |

Kalau validasi gagal → otomatis balik ke form + tampilkan pesan error (lihat `$errors` di Blade).

---

## 3.7 Redirect & Flash Message

Setelah aksi, biasanya pindah halaman + kasih pesan:

```php
return redirect()->route('transaksi.index')
    ->with('success', 'Transaksi berhasil disimpan.');
```

- `redirect()->route('...')` = pindah ke halaman lain.
- `->with('success', '...')` = titip pesan sekali tampil (flash). Di layout ditampilkan:
  ```blade
  @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  ```

---

## ✅ Rangkuman Bab Ini
- Route = daftar URL → controller; pakai `->name()` biar rapi.
- `middleware('auth')` jaga halaman yang butuh login.
- `Route::resource` = 7 route CRUD sekaligus.
- Controller punya 7 fungsi standar (index, create, store, show, edit, update, destroy).
- Route Model Binding = otomatis ambil data by id.
- `$request->validate([...])` = cek input sebelum simpan.
- `redirect()->with('success', ...)` = pindah + pesan.

➡️ Lanjut: [04-blade-view.md](04-blade-view.md)

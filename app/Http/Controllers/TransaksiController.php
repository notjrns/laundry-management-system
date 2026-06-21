<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('layanan')->latest();

        // Pencarian (nama / kode / no hp)
        if ($cari = $request->input('cari')) {
            $query->where(function ($q) use ($cari) {
                $q->where('nama_pelanggan', 'like', "%{$cari}%")
                    ->orWhere('kode', 'like', "%{$cari}%")
                    ->orWhere('no_hp', 'like', "%{$cari}%");
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $layanans = Layanan::orderBy('nama')->get();

        return view('transaksi.create', compact('layanans'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $layanan = Layanan::findOrFail($data['layanan_id']);
        $data['harga_satuan'] = $layanan->harga;
        $data['total_harga'] = (int) round($layanan->harga * (float) $data['berat']);
        $data['kode'] = $this->generateKode();

        // Estimasi selesai otomatis dari paket bila admin tidak mengisinya
        if (empty($data['estimasi_selesai'])) {
            $data['estimasi_selesai'] = $this->hitungEstimasi($layanan);
        }

        $transaksi = Transaksi::create($data);

        $redirect = redirect()->route('transaksi.nota', $transaksi)
            ->with('success', 'Transaksi berhasil disimpan. Nota siap dikirim.');

        // Peringatkan kalau transaksi belum dapat kolom rak (semua rak penuh)
        if ($transaksi->status !== 'diambil'
            && \App\Models\RakKolom::exists()
            && ! \App\Models\RakKolom::where('transaksi_id', $transaksi->id)->exists()) {
            $redirect->with('error', 'Catatan: semua kolom rak penuh, transaksi ini belum mendapat kolom. Kosongkan/ tambah kolom rak.');
        }

        return $redirect;
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load('layanan');

        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $layanans = Layanan::orderBy('nama')->get();

        return view('transaksi.edit', compact('transaksi', 'layanans'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $data = $this->validateData($request);

        $layanan = Layanan::findOrFail($data['layanan_id']);
        $data['harga_satuan'] = $layanan->harga;
        $data['total_harga'] = (int) round($layanan->harga * (float) $data['berat']);

        $transaksi->update($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Halaman nota + tombol kirim WhatsApp.
     */
    public function nota(Transaksi $transaksi)
    {
        $transaksi->load('layanan');

        return view('transaksi.nota', compact('transaksi'));
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'layanan_id' => ['required', 'exists:layanans,id'],
            'berat' => ['required', 'numeric', 'min:0.1'],
            'tanggal_masuk' => ['required', 'date'],
            'estimasi_selesai' => ['nullable', 'date'],
            'status' => ['required', 'in:diproses,selesai,diambil'],
            'status_bayar' => ['required', 'in:belum,lunas'],
            'metode_bayar' => ['required', 'in:cash,transfer'],
            'catatan' => ['nullable', 'string'],
        ]);
    }

    /**
     * Hitung estimasi selesai dari sekarang + estimasi paket layanan.
     */
    private function hitungEstimasi(Layanan $layanan): Carbon
    {
        $now = Carbon::now();

        return $layanan->estimasi_satuan === 'jam'
            ? $now->addHours($layanan->estimasi_nilai)
            : $now->addDays($layanan->estimasi_nilai);
    }

    /**
     * Buat kode nota unik: TRX-YYYYMMDD-0001 (urut per hari).
     */
    private function generateKode(): string
    {
        $tanggal = Carbon::today()->format('Ymd');
        $jumlahHariIni = Transaksi::whereDate('created_at', Carbon::today())->count() + 1;

        return sprintf('TRX-%s-%04d', $tanggal, $jumlahHariIni);
    }
}

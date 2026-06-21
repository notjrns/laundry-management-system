<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::orderBy('nama')->orderBy('estimasi_nilai');

        if ($cari = $request->input('cari')) {
            $query->where('nama', 'like', "%{$cari}%");
        }

        $layanans = $query->paginate(15)->withQueryString();

        return view('layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('layanan.create');
    }

    public function store(Request $request)
    {
        Layanan::create($this->validateData($request));

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $layanan->update($this->validateData($request));

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        if ($layanan->transaksis()->exists()) {
            return back()->with('error', 'Layanan tidak bisa dihapus karena sudah dipakai di transaksi.');
        }

        $layanan->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'in:kg,pcs'],
            'estimasi_nilai' => ['required', 'integer', 'min:1'],
            'estimasi_satuan' => ['required', 'in:jam,hari'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);
    }
}

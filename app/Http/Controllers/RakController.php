<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use Illuminate\Http\Request;

class RakController extends Controller
{
    public function index()
    {
        $raks = Rak::withCount([
            'koloms',
            'koloms as terisi_count' => fn ($q) => $q->where('terisi', true),
        ])->latest()->get();

        return view('rak.index', compact('raks'));
    }

    public function create()
    {
        return view('rak.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $rak = Rak::create($data);
        $rak->generateKolom();

        return redirect()->route('rak.show', $rak)->with('success', 'Rak berhasil dibuat beserta kolomnya.');
    }

    public function show(Rak $rak)
    {
        $rak->load(['koloms.transaksi']);

        return view('rak.show', compact('rak'));
    }

    public function edit(Rak $rak)
    {
        return view('rak.edit', compact('rak'));
    }

    public function update(Request $request, Rak $rak)
    {
        $data = $this->validateData($request);

        $rak->update($data);
        $rak->generateKolom();

        return redirect()->route('rak.show', $rak)->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(Rak $rak)
    {
        $rak->delete();

        return redirect()->route('rak.index')->with('success', 'Rak berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jumlah_kolom' => ['required', 'integer', 'min:1', 'max:200'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }
}

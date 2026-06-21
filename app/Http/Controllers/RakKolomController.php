<?php

namespace App\Http\Controllers;

use App\Models\RakKolom;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RakKolomController extends Controller
{
    /**
     * Form untuk isi / edit isi kolom.
     */
    public function edit(RakKolom $rakKolom)
    {
        $rakKolom->load('rak');

        // Transaksi yang masih relevan untuk ditaruh di rak (belum diambil).
        $transaksis = Transaksi::with('layanan')
            ->whereIn('status', ['diproses', 'selesai'])
            ->latest()
            ->get();

        return view('rak.kolom', compact('rakKolom', 'transaksis'));
    }

    /**
     * Simpan isi kolom (berdasarkan data transaksi atau manual).
     */
    public function update(Request $request, RakKolom $rakKolom)
    {
        $data = $request->validate([
            'transaksi_id' => ['nullable', 'exists:transaksis,id'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'jenis_layanan' => ['nullable', 'string', 'max:255'],
            'estimasi_pengambilan' => ['nullable', 'date'],
            'status' => ['required', 'in:diproses,selesai,diambil'],
        ]);

        $data['terisi'] = true;

        $rakKolom->update($data);

        return redirect()->route('rak.show', $rakKolom->rak_id)
            ->with('success', "Kolom #{$rakKolom->nomor_kolom} berhasil diisi.");
    }

    /**
     * Kosongkan isi kolom (kolomnya tetap ada).
     */
    public function destroy(RakKolom $rakKolom)
    {
        $nomor = $rakKolom->nomor_kolom;
        $rakId = $rakKolom->rak_id;

        $rakKolom->kosongkan();

        return redirect()->route('rak.show', $rakId)
            ->with('success', "Isi kolom #{$nomor} berhasil dikosongkan.");
    }
}

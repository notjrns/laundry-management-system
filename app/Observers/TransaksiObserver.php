<?php

namespace App\Observers;

use App\Models\RakKolom;
use App\Models\Transaksi;

class TransaksiObserver
{
    public function created(Transaksi $transaksi): void
    {
        if ($transaksi->status !== 'diambil') {
            $this->tempatkanKeRak($transaksi);
        }
    }

    public function updated(Transaksi $transaksi): void
    {
        if ($transaksi->status === 'diambil') {
            $this->keluarkanDariRak($transaksi);

            return;
        }

        $kolom = $this->kolomMilik($transaksi);

        if ($kolom) {
            $this->isiKolom($kolom, $transaksi);
        } else {
            $this->tempatkanKeRak($transaksi);
        }
    }

    public function deleted(Transaksi $transaksi): void
    {
        $this->keluarkanDariRak($transaksi);
    }

    private function kolomMilik(Transaksi $transaksi): ?RakKolom
    {
        return RakKolom::where('transaksi_id', $transaksi->id)->first();
    }

    private function tempatkanKeRak(Transaksi $transaksi): void
    {
        $kolom = RakKolom::where('terisi', false)
            ->orderBy('rak_id')
            ->orderBy('nomor_kolom')
            ->first();

        if ($kolom) {
            $this->isiKolom($kolom, $transaksi);
        }
    }

    private function isiKolom(RakKolom $kolom, Transaksi $transaksi): void
    {
        $kolom->update([
            'transaksi_id' => $transaksi->id,
            'nama_pelanggan' => $transaksi->nama_pelanggan,
            'jenis_layanan' => $transaksi->layanan?->nama,
            'estimasi_pengambilan' => $transaksi->estimasi_selesai,
            'status' => $transaksi->status,
            'terisi' => true,
        ]);
    }

    private function keluarkanDariRak(Transaksi $transaksi): void
    {
        RakKolom::where('transaksi_id', $transaksi->id)
            ->get()
            ->each(fn (RakKolom $kolom) => $kolom->kosongkan());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rak extends Model
{
    protected $fillable = [
        'nama',
        'jumlah_kolom',
        'keterangan',
    ];

    public function koloms(): HasMany
    {
        return $this->hasMany(RakKolom::class)->orderBy('nomor_kolom');
    }

    /**
     * Buat / sinkronkan baris kolom sesuai jumlah_kolom.
     */
    public function generateKolom(): void
    {
        for ($i = 1; $i <= $this->jumlah_kolom; $i++) {
            $this->koloms()->firstOrCreate(['nomor_kolom' => $i]);
        }

        // Hapus kolom berlebih (jika jumlah dikurangi) yang masih kosong.
        $this->koloms()
            ->where('nomor_kolom', '>', $this->jumlah_kolom)
            ->where('terisi', false)
            ->delete();
    }
}

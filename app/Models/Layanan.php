<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $fillable = [
        'nama',
        'satuan',
        'estimasi_nilai',
        'estimasi_satuan',
        'harga',
    ];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Label estimasi, contoh: "3 Hari" atau "8 Jam".
     */
    public function estimasiLabel(): string
    {
        return $this->estimasi_nilai . ' ' . ucfirst($this->estimasi_satuan);
    }

    /**
     * Label lengkap untuk dropdown, contoh:
     * "Cuci Gosok (kg) - 3 Hari - Rp 6.000".
     */
    public function labelLengkap(): string
    {
        return sprintf(
            '%s (%s) - %s - Rp %s',
            $this->nama,
            $this->satuan,
            $this->estimasiLabel(),
            number_format($this->harga, 0, ',', '.')
        );
    }
}

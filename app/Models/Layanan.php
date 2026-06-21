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

    public function estimasiLabel(): string
    {
        return $this->estimasi_nilai . ' ' . ucfirst($this->estimasi_satuan);
    }

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

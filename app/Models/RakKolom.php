<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RakKolom extends Model
{
    protected $table = 'rak_koloms';

    protected $fillable = [
        'rak_id',
        'nomor_kolom',
        'transaksi_id',
        'nama_pelanggan',
        'jenis_layanan',
        'estimasi_pengambilan',
        'status',
        'terisi',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_pengambilan' => 'datetime',
            'terisi' => 'boolean',
        ];
    }

    public function rak(): BelongsTo
    {
        return $this->belongsTo(Rak::class);
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function kosongkan(): void
    {
        $this->update([
            'transaksi_id' => null,
            'nama_pelanggan' => null,
            'jenis_layanan' => null,
            'estimasi_pengambilan' => null,
            'status' => null,
            'terisi' => false,
        ]);
    }
}

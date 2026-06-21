<?php

namespace App\Models;

use App\Observers\TransaksiObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(TransaksiObserver::class)]
class Transaksi extends Model
{
    protected $fillable = [
        'kode',
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'layanan_id',
        'berat',
        'harga_satuan',
        'total_harga',
        'tanggal_masuk',
        'estimasi_selesai',
        'status',
        'status_bayar',
        'metode_bayar',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'berat' => 'decimal:2',
            'tanggal_masuk' => 'date',
            'estimasi_selesai' => 'datetime',
        ];
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    /**
     * Label warna badge untuk status (dipakai di view).
     */
    public function statusBadge(): string
    {
        return match ($this->status) {
            'diproses' => 'warning',
            'selesai' => 'info',
            'diambil' => 'success',
            default => 'secondary',
        };
    }
}

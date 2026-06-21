<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Layanan;
use App\Models\Rak;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin default
        User::updateOrCreate(
            ['email' => 'admin@laundry.test'],
            [
                'name' => 'Admin Laundry',
                'password' => Hash::make('password'),
            ]
        );

        // Daftar paket layanan (nama, satuan, estimasi nilai, estimasi satuan, harga)
        $layanans = [
            ['Cuci Gosok', 'kg', 3, 'hari', 6000],
            ['Cuci Gosok', 'kg', 2, 'hari', 7000],
            ['Cuci Gosok', 'kg', 1, 'hari', 8000],
            ['Cuci Gosok', 'kg', 8, 'jam', 11000],
            ['Cuci Gosok', 'kg', 6, 'jam', 13000],
            ['Cuci Lipat', 'kg', 3, 'hari', 4000],
            ['Cuci Lipat', 'kg', 2, 'hari', 5000],
            ['Cuci Lipat', 'kg', 1, 'hari', 6000],
            ['Cuci Lipat', 'kg', 8, 'jam', 9000],
            ['Cuci Lipat', 'kg', 6, 'jam', 11000],
            ['Cuci Bed Cover', 'pcs', 3, 'hari', 25000],
            ['Cuci Bed Cover', 'pcs', 2, 'hari', 30000],
            ['Cuci Bed Cover', 'pcs', 1, 'hari', 35000],
            ['Cuci Selimut', 'pcs', 3, 'hari', 20000],
            ['Cuci Selimut', 'pcs', 2, 'hari', 25000],
            ['Cuci Selimut', 'pcs', 1, 'hari', 30000],
        ];

        foreach ($layanans as [$nama, $satuan, $estimasiNilai, $estimasiSatuan, $harga]) {
            Layanan::updateOrCreate(
                ['nama' => $nama, 'estimasi_nilai' => $estimasiNilai, 'estimasi_satuan' => $estimasiSatuan],
                ['satuan' => $satuan, 'harga' => $harga]
            );
        }

        // Contoh karyawan
        Karyawan::firstOrCreate(
            ['nama' => 'Anis'],
            ['jabatan' => 'Kasir', 'no_hp' => '081234567890', 'status' => 'aktif', 'tanggal_masuk' => now()]
        );

        // Contoh rak (otomatis dibuatkan 20 kolom)
        $rak = Rak::firstOrCreate(['nama' => 'Rak A'], ['jumlah_kolom' => 20]);
        $rak->generateKolom();
    }
}

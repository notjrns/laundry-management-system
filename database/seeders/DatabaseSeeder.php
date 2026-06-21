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

        // Daftar layanan + harga per kg
        $layanans = [
            ['nama' => 'Cuci Kering', 'harga' => 5000],
            ['nama' => 'Cuci Setrika', 'harga' => 7000],
            ['nama' => 'Setrika Saja', 'harga' => 4000],
            ['nama' => 'Cuci Express (Kilat)', 'harga' => 12000],
            ['nama' => 'Cuci Selimut/Bedcover', 'harga' => 15000],
        ];

        foreach ($layanans as $layanan) {
            Layanan::updateOrCreate(['nama' => $layanan['nama']], $layanan);
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

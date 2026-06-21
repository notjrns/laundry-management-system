<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Transaksi;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        $stats = [
            'transaksi_hari_ini' => Transaksi::whereDate('tanggal_masuk', $hariIni)->count(),
            'diproses' => Transaksi::where('status', 'diproses')->count(),
            'selesai' => Transaksi::where('status', 'selesai')->count(),
            'karyawan' => Karyawan::where('status', 'aktif')->count(),
            'pendapatan_hari_ini' => (int) Transaksi::whereDate('tanggal_masuk', $hariIni)->sum('total_harga'),
            'pendapatan_bulan_ini' => (int) Transaksi::whereMonth('tanggal_masuk', $hariIni->month)
                ->whereYear('tanggal_masuk', $hariIni->year)
                ->sum('total_harga'),
        ];

        $terbaru = Transaksi::with('layanan')->latest()->take(8)->get();

        return view('dashboard', compact('stats', 'terbaru'));
    }
}

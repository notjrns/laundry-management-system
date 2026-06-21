<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        [$dari, $sampai, $label] = $this->resolveRange($request);

        $transaksis = Transaksi::with('layanan')
            ->whereBetween('tanggal_masuk', [$dari, $sampai])
            ->orderBy('tanggal_masuk')
            ->get();

        $ringkasan = [
            'jumlah' => $transaksis->count(),
            'total' => (int) $transaksis->sum('total_harga'),
            'lunas' => (int) $transaksis->where('status_bayar', 'lunas')->sum('total_harga'),
            'belum' => (int) $transaksis->where('status_bayar', 'belum')->sum('total_harga'),
        ];

        return view('laporan.index', [
            'transaksis' => $transaksis,
            'ringkasan' => $ringkasan,
            'label' => $label,
            'dari' => $dari,
            'sampai' => $sampai,
            'periode' => $request->input('periode', 'hari_ini'),
            'tgl_dari' => $request->input('tgl_dari'),
            'tgl_sampai' => $request->input('tgl_sampai'),
        ]);
    }

    public function pdf(Request $request)
    {
        [$dari, $sampai, $label] = $this->resolveRange($request);

        $transaksis = Transaksi::with('layanan')
            ->whereBetween('tanggal_masuk', [$dari, $sampai])
            ->orderBy('tanggal_masuk')
            ->get();

        $ringkasan = [
            'jumlah' => $transaksis->count(),
            'total' => (int) $transaksis->sum('total_harga'),
            'lunas' => (int) $transaksis->where('status_bayar', 'lunas')->sum('total_harga'),
            'belum' => (int) $transaksis->where('status_bayar', 'belum')->sum('total_harga'),
        ];

        $pdf = Pdf::loadView('laporan.pdf', compact('transaksis', 'ringkasan', 'label', 'dari', 'sampai'))
            ->setPaper('a4', 'portrait');

        $namaFile = 'laporan-laundry-' . $dari->format('Ymd') . '-' . $sampai->format('Ymd') . '.pdf';

        return $pdf->download($namaFile);
    }

    /**
     * Tentukan rentang tanggal dari pilihan periode / tanggal custom.
     *
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function resolveRange(Request $request): array
    {
        $periode = $request->input('periode', 'hari_ini');

        return match ($periode) {
            'minggu_ini' => [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
                'Minggu Ini (' . Carbon::now()->startOfWeek()->format('d/m/Y') . ' - ' . Carbon::now()->endOfWeek()->format('d/m/Y') . ')',
            ],
            'bulan_ini' => [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
                'Bulan ' . Carbon::now()->translatedFormat('F Y'),
            ],
            'custom' => $this->customRange($request),
            default => [
                Carbon::today(),
                Carbon::today()->endOfDay(),
                'Hari Ini (' . Carbon::today()->format('d/m/Y') . ')',
            ],
        };
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function customRange(Request $request): array
    {
        $request->validate([
            'tgl_dari' => ['required', 'date'],
            'tgl_sampai' => ['required', 'date', 'after_or_equal:tgl_dari'],
        ]);

        $dari = Carbon::parse($request->input('tgl_dari'))->startOfDay();
        $sampai = Carbon::parse($request->input('tgl_sampai'))->endOfDay();

        return [$dari, $sampai, 'Periode ' . $dari->format('d/m/Y') . ' - ' . $sampai->format('d/m/Y')];
    }
}

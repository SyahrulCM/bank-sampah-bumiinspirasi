<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrasi;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $jumlahRegistrasi = Registrasi::count();
        $jumlahTransaksi = Transaksi::count();
        $jumlahPenjualan = Penjualan::count();
        $jumlahSampah = DetailTransaksi::sum('berat_sampah');

        // Grafik transaksi per bulan
        $data = Transaksi::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

        $chartBulan = [];
        $chartJumlah = [];
        foreach ($data as $d) {
            $bulanNama = Carbon::create()->month($d->bulan)->format('F') . ' ' . $d->tahun;
            $chartBulan[] = $bulanNama;
            $chartJumlah[] = $d->jumlah;
        }

        // Leaderboard saldo nasabah terbanyak (top 10)
        $topNasabah = Registrasi::orderByDesc('saldo')
            ->select('nama_lengkap', 'saldo')
            ->limit(10)
            ->get();

        // Grafik penjualan per bulan
        $dataPenjualan = Penjualan::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('COUNT(*) as jumlah')
        )
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

        $chartBulanPenjualan = [];
        $chartJumlahPenjualan = [];
        foreach ($dataPenjualan as $d) {
            $bulanNama = Carbon::create()->month($d->bulan)->format('F') . ' ' . $d->tahun;
            $chartBulanPenjualan[] = $bulanNama;
            $chartJumlahPenjualan[] = $d->jumlah;
        }

        return view('dashboard', compact(
            'jumlahRegistrasi', 'jumlahTransaksi', 'jumlahSampah',
            'chartBulan', 'chartJumlah',
            'chartBulanPenjualan', 'chartJumlahPenjualan', 'jumlahPenjualan',
            'topNasabah'
        ));
    }
}

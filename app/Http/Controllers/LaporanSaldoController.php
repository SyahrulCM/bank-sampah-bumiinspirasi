<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Penarikan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

class LaporanSaldoController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('pengepul')->get();
        $penarikans = Penarikan::with('registrasi')->get();

        $laporan = collect();
        $totalMasuk = 0;
        $totalKeluar = 0;

        foreach ($penjualans as $penjualan) {
            $namaPengepul = $penjualan->pengepul->nama_pengepul ?? '-';
            $jumlah = $penjualan->total_harga;
            $totalMasuk += $jumlah;

            $laporan->push([
                'tanggal' => $penjualan->tanggal,
                'aksi' => 'Masuk',
                'nama' => $namaPengepul,
                'jumlah' => $jumlah,
                'keterangan' => 'Penjualan ke pengepul ' . $namaPengepul
            ]);
        }

        foreach ($penarikans as $penarikan) {
            $namaNasabah = $penarikan->registrasi->nama_lengkap ?? '-';
            $jumlah = $penarikan->jumlah;
            $totalKeluar += $jumlah;

            $laporan->push([
                'tanggal' => $penarikan->tanggal,
                'aksi' => 'Keluar',
                'nama' => $namaNasabah,
                'jumlah' => $jumlah,
                'keterangan' => 'Penarikan oleh nasabah ' . $namaNasabah
            ]);
        }

        $laporan = $laporan->sortByDesc('tanggal');
        $totalSaldo = $totalMasuk - $totalKeluar;

        return view('layout.laporan_saldo', compact('laporan', 'totalMasuk', 'totalKeluar', 'totalSaldo'));
    }

    public function exportManualExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $penjualan = Penjualan::with('pengepul')
            ->whereBetween('tanggal', [$from, $to])
            ->get();

        $penarikan = Penarikan::with('registrasi')
            ->whereBetween('tanggal', [$from, $to])
            ->get();

        $laporan = collect();

        foreach ($penjualan as $item) {
            $laporan->push([
                'tanggal' => $item->tanggal,
                'jenis' => 'Masuk',
                'nama' => $item->pengepul->nama_pengepul ?? '-',
                'jumlah' => $item->total_harga,
                'keterangan' => 'Penjualan ke pengepul',
            ]);
        }

        foreach ($penarikan as $item) {
            $laporan->push([
                'tanggal' => $item->tanggal,
                'jenis' => 'Keluar',
                'nama' => $item->registrasi->nama_lengkap ?? '-',
                'jumlah' => $item->jumlah,
                'keterangan' => 'Penarikan oleh nasabah',
            ]);
        }

        $sortedData = $laporan->sortByDesc('tanggal')->values();

        $totalMasuk = $laporan->where('jenis', 'Masuk')->sum('jumlah');
        $totalKeluar = $laporan->where('jenis', 'Keluar')->sum('jumlah');
        $totalSaldo = $totalMasuk - $totalKeluar;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Aksi');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Jumlah (Rp)');
        $sheet->setCellValue('F1', 'Keterangan');

        // Isi data
        $row = 2;
        $no = 1;

        foreach ($sortedData as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, Carbon::parse($item['tanggal'])->format('d-m-Y'));
            $sheet->setCellValue('C' . $row, $item['jenis']);
            $sheet->setCellValue('D' . $row, $item['nama']);
            $sheet->setCellValue('E' . $row, $item['jumlah']);
            $sheet->setCellValue('F' . $row, $item['keterangan']);
            $row++;
        }

        // Baris total
        $sheet->setCellValue('D' . $row, 'Total Masuk');
        $sheet->setCellValue('E' . $row, $totalMasuk);
        $row++;

        $sheet->setCellValue('D' . $row, 'Total Keluar');
        $sheet->setCellValue('E' . $row, $totalKeluar);
        $row++;

        $sheet->setCellValue('D' . $row, 'Saldo Akhir');
        $sheet->setCellValue('E' . $row, $totalSaldo);

        // Format angka ke dalam bentuk rupiah
        for ($i = 2; $i <= $row; $i++) {
            $val = $sheet->getCell('E' . $i)->getValue();
            $sheet->setCellValue('E' . $i, 'Rp ' . number_format($val, 0, ',', '.'));
        }

        // Tambahkan border ke seluruh tabel
        $sheet->getStyle('A1:F' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Simpan dan download
        $filename = 'laporan_saldo_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}

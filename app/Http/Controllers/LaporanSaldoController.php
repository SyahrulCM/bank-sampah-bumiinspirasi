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

        // ====== Judul dan Info ======
        $sheet->mergeCells('B1:G1');
        $sheet->setCellValue('B1', 'LAPORAN KEUANGAN');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('B2:G2');
        $sheet->setCellValue('B2', 'Periode: ' . Carbon::parse($from)->format('d M Y') . ' - ' . Carbon::parse($to)->format('d M Y'));
        $sheet->getStyle('B2')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('B3:G3');
        $sheet->setCellValue('B3', 'Tanggal Export: ' . Carbon::now()->format('d M Y H:i'));
        $sheet->getStyle('B3')->getAlignment()->setHorizontal('center');

        $startRow = 5;

        // ====== Header Tabel (Mulai dari Kolom B) ======
        $sheet->setCellValue('B' . $startRow, 'No');
        $sheet->setCellValue('C' . $startRow, 'Tanggal');
        $sheet->setCellValue('D' . $startRow, 'Aksi');
        $sheet->setCellValue('E' . $startRow, 'Nama');
        $sheet->setCellValue('F' . $startRow, 'Jumlah (Rp)');
        $sheet->setCellValue('G' . $startRow, 'Keterangan');

        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getFont()->setBold(true);

        // ====== Isi Data ======
        $row = $startRow + 1;
        $no = 1;

        foreach ($sortedData as $item) {
            $sheet->setCellValue('B' . $row, $no++);
            $sheet->setCellValue('C' . $row, Carbon::parse($item['tanggal'])->format('d-m-Y'));
            $sheet->setCellValue('D' . $row, $item['jenis']);
            $sheet->setCellValue('E' . $row, $item['nama']);
            $sheet->setCellValue('F' . $row, $item['jumlah']);
            $sheet->setCellValue('G' . $row, $item['keterangan']);
            $row++;
        }

        // ====== Total ======
        $sheet->setCellValue('E' . $row, 'Total Masuk');
        $sheet->setCellValue('F' . $row, $totalMasuk);
        $row++;

        $sheet->setCellValue('E' . $row, 'Total Keluar');
        $sheet->setCellValue('F' . $row, $totalKeluar);
        $row++;

        $sheet->setCellValue('E' . $row, 'Saldo Akhir');
        $sheet->setCellValue('F' . $row, $totalSaldo);

        // ====== Format Rupiah ======
        for ($i = $startRow + 1; $i <= $row; $i++) {
            $val = $sheet->getCell('F' . $i)->getValue();
            if (is_numeric(str_replace(['Rp', '.', ','], '', $val))) {
                $sheet->setCellValue('F' . $i, 'Rp ' . number_format($val, 0, ',', '.'));
            }
        }

        // ====== Border Tabel ======
        $sheet->getStyle('B' . $startRow . ':G' . $row)
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Simpan dan download
        $filename = 'laporan_saldo_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}

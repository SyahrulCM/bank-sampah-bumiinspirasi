<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Penarikan;
use App\Models\Transaksi;
use App\Models\Sampah;
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
        $sheet->setCellValue('B1', 'LAPORAN KEUANGAN MASUK DAN KELUAR BANK SAMPAH BUMI INSPIRASI');
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

         $sheet->getStyle('B1:G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1:G1')->getAlignment()->setHorizontal('center');
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('B')->setWidth(7);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(22);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getStyle('B' . $startRow . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('B' . $startRow . ':G' . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $startRow . ':G' . $row)->getAlignment()->setVertical('center');
        $sheet->getStyle('F' . ($row-2) . ':F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E' . ($row-2) . ':F' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FCE4D6');

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportTransaksiExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $transaksis = Transaksi::with('detailTransaksi.sampah', 'nasabah')
            ->whereBetween('tanggal', [$from, $to])
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ====== Judul dan Info ======
        $sheet->mergeCells('B1:G1');
        $sheet->setCellValue('B1', 'LAPORAN TRANSAKSI NASABAH BANK SAMPAH BUMI INSPIRASI');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('B2:G2');
        $sheet->setCellValue('B2', 'Periode: ' . Carbon::parse($from)->format('d M Y') . ' - ' . Carbon::parse($to)->format('d M Y'));
        $sheet->getStyle('B2')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('B3:G3');
        $sheet->setCellValue('B3', 'Tanggal Export: ' . Carbon::now()->format('d M Y H:i'));
        $sheet->getStyle('B3')->getAlignment()->setHorizontal('center');

        $startRow = 5;

        // ====== Header Tabel ======
        $sheet->setCellValue('B' . $startRow, 'No');
        $sheet->setCellValue('C' . $startRow, 'Tanggal');
        $sheet->setCellValue('D' . $startRow, 'Nama Nasabah');
        $sheet->setCellValue('E' . $startRow, 'Jenis Sampah');
        $sheet->setCellValue('F' . $startRow, 'Berat (Kg)');
        $sheet->setCellValue('G' . $startRow, 'Total Harga');

        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getFont()->setBold(true);

        // ====== Isi Data ======
        $row = $startRow + 1;
        $no = 1;
        foreach ($transaksis as $t) {
            $jenisSampahList = [];
            $beratSampahList = [];

            foreach ($t->detailTransaksi as $detail) {
                $jenis = $detail->sampah->jenis_sampah ?? '-';
                $berat = $detail->berat_sampah;
                $jenisSampahList[] = '-' . $jenis . ' ';
                $beratSampahList[] = '-' . $berat . ' ';
            }

            $sheet->setCellValue('B' . $row, $no++);
            $sheet->setCellValue('C' . $row, Carbon::parse($t->tanggal)->format('d-m-Y'));
            $sheet->setCellValue('D' . $row, $t->registrasi->nama_lengkap ?? '-');
            $sheet->setCellValue('E' . $row, implode("\n", $jenisSampahList));
            $sheet->setCellValue('F' . $row, implode("\n", $beratSampahList));
            $sheet->setCellValue('G' . $row, 'Rp ' . number_format($t->saldo, 0, ',', '.'));
            $row++;
        }
            $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true);

        // ====== Border ======
        $sheet->getStyle('B' . $startRow . ':G' . $row)
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Simpan dan kirim file
        $filename = 'laporan_transaksi_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

         // Styling header dan kolom (setelah data diisi)
        $sheet->getStyle('B1:G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1:G1')->getAlignment()->setHorizontal('center');
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $startRow . ':G' . $startRow)->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('B')->setWidth(7);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(22);
        $sheet->getColumnDimension('E')->setWidth(22);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getStyle('B' . $startRow . ':G' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('B' . $startRow . ':G' . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $startRow . ':G' . $row)->getAlignment()->setVertical('center');
    

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function exportStokExcel()
    {
        $stok = Sampah::all(); // Atau sesuaikan jika ada relasi/penyaringan khusus

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ====== Judul Laporan ======
        $sheet->mergeCells('B1:F1');
        $sheet->setCellValue('B1', 'LAPORAN STOK SAMPAH BANK SAMPAH BUMI INSPIRASI');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal('center');

        $sheet->mergeCells('B2:F2');
        $sheet->setCellValue('B2', 'Tanggal Export: ' . Carbon::now()->format('d M Y H:i'));
        $sheet->getStyle('B2')->getAlignment()->setHorizontal('center');

        $startRow = 4;

        // ====== Header Tabel ======
        $sheet->setCellValue('B' . $startRow, 'No');
        $sheet->setCellValue('C' . $startRow, 'Jenis Sampah');
        $sheet->setCellValue('D' . $startRow, 'Kategori');
        $sheet->setCellValue('E' . $startRow, 'Harga per Kg');
        $sheet->setCellValue('F' . $startRow, 'Stok Tersedia (Kg)');

        $sheet->getStyle('B' . $startRow . ':F' . $startRow)->getFont()->setBold(true);

        // ====== Isi Data ======
        $row = $startRow + 1;
        $no = 1;
        foreach ($stok as $item) {
            $sheet->setCellValue('B' . $row, $no++);
            $sheet->setCellValue('C' . $row, $item->jenis_sampah);
            $sheet->setCellValue('D' . $row, $item->kategori);
            $sheet->setCellValue('E' . $row, $item->harga);
            $sheet->setCellValue('F' . $row, $item->stok);
            $row++;
        }

        // ====== Format Harga ======
        for ($i = $startRow + 1; $i < $row; $i++) {
            $val = $sheet->getCell('E' . $i)->getValue();
            if (is_numeric($val)) {
                $sheet->setCellValue('E' . $i, 'Rp ' . number_format($val, 0, ',', '.'));
            }
        }

        // ====== Border Tabel ======
        $sheet->getStyle('B' . $startRow . ':F' . ($row - 1))
            ->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Simpan dan download
        $filename = 'laporan_stok_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $sheet->getStyle('B1:F1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1:F1')->getAlignment()->setHorizontal('center');
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('B' . $startRow . ':F' . $startRow)->getFont()->setBold(true);
        $sheet->getStyle('B' . $startRow . ':F' . $startRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
        $sheet->getStyle('B' . $startRow . ':F' . $startRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $startRow . ':F' . $startRow)->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('B')->setWidth(7);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getStyle('B' . $startRow . ':F' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('B' . $startRow . ':F' . ($row - 1))->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B' . $startRow . ':F' . ($row - 1))->getAlignment()->setVertical('center');

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Stok;
use App\Models\Sampah;
use App\Models\Registrasi;
use App\Models\Mutasi;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['detailTransaksi.sampah', 'nasabah'])->latest()->get();
        $nasabah = Registrasi::all();
        $sampah = Sampah::all();

        return view('layout.transaksi', compact('transaksis', 'nasabah', 'sampah'));
    }

    public function simpanTransaksi(Request $request)
    {
        DB::beginTransaction();
        try {
            $tanggal = $request->tanggal ?? date('Y-m-d');

            // Cek apakah transaksi dengan nasabah dan tanggal yang sama sudah ada
            $transaksi = Transaksi::where('id_registrasi', $request->id_registrasi)
                                  ->where('tanggal', $tanggal)
                                  ->first();

            if (!$transaksi) {
                $transaksi = Transaksi::create([
                    'id_registrasi' => $request->id_registrasi,
                    'tanggal' => $tanggal,
                    'saldo' => 0,
                ]);
            }

            $totalSaldoBaru = 0;
            $beratSampah = $request->berat_sampah;

            foreach ($request->id_sampah as $index => $id_sampah) {
                $beratBaru = (float) str_replace(',', '.', $beratSampah[$index]);

                // Cari detail transaksi untuk sampah ini
                $detail = DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)
                            ->where('id_sampah', $id_sampah)
                            ->first();

                if ($detail) {
                    // Jika sudah ada, update berat dan jumlah setoran
                    $detail->berat_sampah += $beratBaru;
                    $detail->jumlah_setoran += 1;
                    $detail->save();
                } else {
                    // Jika belum ada, buat data baru
                    DetailTransaksi::create([
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_sampah' => $id_sampah,
                        'berat_sampah' => $beratBaru,
                        'tanggal' => $tanggal,
                        'jumlah_setoran' => 1,
                    ]);
                }

                // Update stok
                $stok = Stok::where('id_sampah', $id_sampah)->first();
                if ($stok) {
                    $stok->jumlah += $beratBaru;
                    $stok->save();
                } else {
                    Stok::create([
                        'id_sampah' => $id_sampah,
                        'jumlah' => $beratBaru,
                        'tanggal' => $tanggal,
                    ]);
                }

                // Mutasi
                $nasabah = Registrasi::find($request->id_registrasi);
                Mutasi::create([
                    'tanggal' => $tanggal,
                    'id_sampah' => $id_sampah,
                    'aksi' => 'Masuk',
                    'berat' => $beratBaru,
                    'keterangan' => 'Setoran dari nasabah ' . ($nasabah ? $nasabah->nama_lengkap : '-'),
                ]);

                // Hitung saldo tambahan
                $harga = Sampah::find($id_sampah)->harga_ditabung ?? 0;
                $totalSaldoBaru += $beratBaru * $harga;
            }

            // Tambahkan ke saldo transaksi
            $transaksi->saldo += $totalSaldoBaru;
            $transaksi->save();

            // ✅ Tambahkan saldo ke nasabah
            $nasabah = Registrasi::find($request->id_registrasi);
            $nasabah->saldo += $totalSaldoBaru;
            $nasabah->save();

            DB::commit();

            return redirect()->route('layout.transaksi')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.sampah', 'nasabah'])->findOrFail($id);
        return view('layout.detail_transaksi', compact('transaksi'));
    }

    public function hapusTransaksi($id)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);

            // Kurangi stok sampah
            foreach ($transaksi->detailTransaksi as $detail) {
                $stok = Stok::where('id_sampah', $detail->id_sampah)->first();
                if ($stok) {
                    $stok->jumlah -= $detail->berat_sampah;
                    if ($stok->jumlah < 0) $stok->jumlah = 0;
                    $stok->save();
                }
            }

            // Kurangi saldo nasabah
            $nasabah = Registrasi::find($transaksi->id_registrasi);
            if ($nasabah) {
                $nasabah->saldo -= $transaksi->saldo;
                if ($nasabah->saldo < 0) $nasabah->saldo = 0;
                $nasabah->save();
            }

            // Hapus detail dan transaksi
            DetailTransaksi::where('id_transaksi', $transaksi->id_transaksi)->delete();
            $transaksi->delete();

            DB::commit();
            return redirect()->route('layout.transaksi')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }


    public function exportManualExcel(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $transaksis = Transaksi::with('detailTransaksi.sampah', 'nasabah')
            ->whereBetween('tanggal', [$from, $to])
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Nama Nasabah');
        $sheet->setCellValue('D1', 'Jenis Sampah');
        $sheet->setCellValue('E1', 'Berat Sampah (kg)');
        $sheet->setCellValue('F1', 'Saldo Ditambah');

        $row = 2;
        $no = 1;

        foreach ($transaksis as $t) {
            $jenisSampahList = [];
            $beratSampahList = [];

            foreach ($t->detailTransaksi as $detail) {
                $jenis = $detail->sampah->jenis_sampah ?? '-';
                $berat = $detail->berat_sampah;
                $jenisSampahList[] = '• ' . $jenis;
                $beratSampahList[] = $berat;
            }

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, Carbon::parse($t->tanggal)->format('d-m-Y'));
            $sheet->setCellValue('C' . $row, $t->nasabah->nama_lengkap ?? '-');
            $sheet->setCellValue('D' . $row, implode("\n", $jenisSampahList));
            $sheet->setCellValue('E' . $row, implode("\n", $beratSampahList));
            $sheet->setCellValue('F' . $row, 'Rp ' . number_format($t->saldo, 0, ',', '.'));

            // Aktifkan text wrap agar line break terlihat
            $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true);

            $row++;
        }

        $filename = 'transaksi_' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}

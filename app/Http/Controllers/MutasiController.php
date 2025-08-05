<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutasi;
use App\Models\Stok;
use App\Models\Sampah;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    // Menampilkan semua mutasi
    public function index()
    {
        $mutasis = Mutasi::with('sampah')->latest()->get();
        $sampahs = Sampah::all();
        return view('layout.mutasi', compact('mutasis', 'sampahs'));
    }

    // Export laporan mutasi ke Excel
    public function exportLaporan(Request $request)
    {   
    
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date',
            'aksi' => 'nullable|in:Masuk,Keluar,Semua',
        ]);

        $query = Mutasi::with('sampah')
            ->whereDate('tanggal', '>=', $request->from)
            ->whereDate('tanggal', '<=', $request->to);
        if ($request->aksi && $request->aksi != 'Semua') {
            $query->where('aksi', $request->aksi);
        }
        $mutasis = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header utama
        $sheet->setCellValue('A1', 'LAPORAN MUTASI SAMPAH');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Info periode dan waktu export
        $sheet->setCellValue('A2', 'Periode:');
        $sheet->setCellValue('B2', date('d-m-Y', strtotime($request->from)) . ' s/d ' . date('d-m-Y', strtotime($request->to)));
        $sheet->setCellValue('D2', 'Waktu Export:');
        $sheet->setCellValue('E2', date('d-m-Y H:i:s'));

        // Header kolom data
        $sheet->setCellValue('A4', 'Tanggal');
        $sheet->setCellValue('B4', 'Jenis Sampah');
        $sheet->setCellValue('C4', 'Aksi');
        $sheet->setCellValue('D4', 'Berat (kg)');
        $sheet->setCellValue('E4', 'Keterangan');
        $sheet->getStyle('A4:E4')->getFont()->setBold(true);

        $rowNum = 5;
        $totalBerat = 0;
        foreach ($mutasis as $m) {
            $sheet->setCellValue('A' . $rowNum, $m->tanggal);
            $sheet->setCellValue('B' . $rowNum, $m->sampah->jenis_sampah ?? '-');
            $sheet->setCellValue('C' . $rowNum, $m->aksi);
            $sheet->setCellValue('D' . $rowNum, $m->berat);
            $sheet->setCellValue('E' . $rowNum, $m->keterangan);
            $totalBerat += $m->berat;
            $rowNum++;
        }

        // Total berat keseluruhan
        $sheet->setCellValue('C' . $rowNum, 'Total Berat');
        $sheet->setCellValue('D' . $rowNum, $totalBerat);

        // Styling header utama
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('A4:E4')->getFont()->setBold(true);
        $sheet->getStyle('A4:E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
        $sheet->getStyle('A4:E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:E4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Styling border dan kolom
        $lastDataRow = $rowNum - 1;
        $borderRange = 'A4:E' . $lastDataRow;
        $sheet->getStyle($borderRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($borderRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($borderRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(35);

        // Styling total berat
        $sheet->getStyle('C' . $rowNum . ':D' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('C' . $rowNum . ':D' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FCE4D6');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Laporan_Mutasi_' . date('Ymd_His') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        // ...existing code...
        // Styling setelah data selesai diisi
        $sheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('A4:E4')->getFont()->setBold(true);
        $sheet->getStyle('A4:E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
        $sheet->getStyle('A4:E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:E4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Styling border dan kolom
        $lastDataRow = $rowNum - 1;
        $borderRange = 'A4:E' . $lastDataRow;
        $sheet->getStyle($borderRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle($borderRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($borderRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(35);

        // Styling total berat
        $sheet->getStyle('C' . $rowNum . ':D' . $rowNum)->getFont()->setBold(true);
        $sheet->getStyle('C' . $rowNum . ':D' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FCE4D6');

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    // Menyimpan mutasi manual
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_sampah' => 'required|exists:sampahs,id_sampah',
            'aksi' => 'required|in:Masuk,Keluar',
            'berat' => 'required|numeric|min:0.01',
            'keterangan' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $stok = Stok::firstOrCreate(
                ['id_sampah' => $request->id_sampah],
                ['jumlah' => 0, 'tanggal' => now()]
            );

            // Cek stok jika aksi adalah "Keluar"
            if ($request->aksi === 'Keluar') {
                if ($stok->jumlah <= 0) {
                    return back()->with('error', 'Stok sampah tidak tersedia.');
                }
                if ($stok->jumlah < $request->berat) {
                    return back()->with('error', 'Stok tidak mencukupi untuk pengurangan sampah.');
                }
            }

            // Selalu buat mutasi baru setiap kali store dipanggil
            Mutasi::create([
                'tanggal' => $request->tanggal,
                'id_sampah' => $request->id_sampah,
                'aksi' => $request->aksi,
                'berat' => $request->berat,
                'keterangan' => $request->keterangan,
            ]);

            // Update stok
            if ($request->aksi === 'Masuk') {
                $stok->jumlah += $request->berat;
            } else {
                $stok->jumlah -= $request->berat;
            }

            $stok->save();

            DB::commit();
            return redirect()->route('mutasi.index')->with('success', 'Mutasi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan mutasi: ' . $e->getMessage());
        }
    }

    // Menghapus mutasi dan mengembalikan stok
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $mutasi = Mutasi::findOrFail($id);
            $stok = Stok::where('id_sampah', $mutasi->id_sampah)->first();

            if ($stok) {
                if ($mutasi->aksi === 'Masuk') {
                    $stok->jumlah -= $mutasi->berat;
                } else {
                    $stok->jumlah += $mutasi->berat;
                }

                if ($stok->jumlah < 0) {
                    $stok->jumlah = 0;
                }

                $stok->save();
            }

            $mutasi->delete();

            DB::commit();
            return redirect()->route('mutasi.index')->with('success', 'Mutasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus mutasi: ' . $e->getMessage());
        }
    }
}

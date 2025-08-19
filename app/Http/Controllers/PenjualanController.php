<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Sampah;
use App\Models\Stok;
use App\Models\Pengepul;
use App\Models\Mutasi;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['pengepul', 'detailPenjualan'])->get();
        $sampahs = Sampah::all();
        // Tambahkan properti stok ke setiap sampah
        foreach ($sampahs as $s) {
            $stok = Stok::where('id_sampah', $s->id_sampah)->first();
            $s->stok = $stok ? $stok->jumlah : 0;
        }
        $pengepuls = Pengepul::all();

        return view('layout.penjualan', compact('penjualans', 'sampahs', 'pengepuls'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id_pengepul' => 'required|exists:pengepuls,id_pengepul',
            'tanggal' => 'required|date',
            'id_sampah' => 'required|array|min:1',
            'id_sampah.*' => 'required|exists:sampahs,id_sampah',
            'berat' => 'required|array|min:1',
            'berat.*' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            foreach ($request->id_sampah as $index => $id_sampah) {
                $berat = $request->berat[$index];
                $sampah = Sampah::findOrFail($id_sampah);

                $stok = Stok::where('id_sampah', $id_sampah)->first();
                if (!$stok || $stok->jumlah < $berat) {
                    throw new \Exception("Stok tidak mencukupi untuk jenis sampah: {$sampah->jenis_sampah}");
                }

                $totalHarga += $berat * $sampah->harga_pengepul;
            }

            $penjualan = Penjualan::create([
                'id_pengepul' => $request->id_pengepul,
                'tanggal' => $request->tanggal,
                'total_harga' => $totalHarga,
            ]);

            foreach ($request->id_sampah as $i => $idSampah) {
                $berat = $request->berat[$i];
                $sampah = Sampah::findOrFail($idSampah);
                $stok = Stok::where('id_sampah', $idSampah)->first();

                if (!$stok || $stok->jumlah < $berat) {
                    return back()->with('error', 'Stok tidak mencukupi untuk jenis sampah: ' . $sampah->jenis_sampah);
                }

                $subtotal = $sampah->harga_pengepul * $berat;

                // Tidak ada perubahan stok di sini
                // $stok->jumlah -= $berat;
                // $stok->save();

                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_sampah' => $idSampah,
                    'berat_kg' => $berat,
                    'subtotal' => $subtotal,
                ]);

                $pengepul = Pengepul::find($request->id_pengepul);
                $namaPengepul = $pengepul ? $pengepul->nama_pengepul : 'Tanpa Nama';

                Mutasi::create([
                    'tanggal' => $request->tanggal,
                    'id_sampah' => $idSampah,
                    'aksi' => 'Keluar',
                    'berat' => $berat,
                    'keterangan' => 'Penjualan ke pengepul ' . $namaPengepul,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Penjualan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $penjualan = Penjualan::with('detailPenjualan.sampah')->findOrFail($id);
        return view('layout.detail_penjualan', compact('penjualan'));
    }

    public function hapus($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->detailPenjualan()->delete();
        $penjualan->delete();

        return redirect()->route('penjualans.index')->with('success', 'Data penjualan berhasil dihapus.');
    }

    public function validasiHarga(Request $request, $id)
    {
        $request->validate([
            'hasil_negosiasi' => 'required|integer|min:0',
        ]);
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->hasil_negosiasi = $request->hasil_negosiasi;
        $penjualan->save();
        return redirect()->route('penjualans.index')->with('success', 'Harga negosiasi berhasil divalidasi.');
    }

    public function invoice($id)
    {
        $penjualan = Penjualan::with(['pengepul', 'detailPenjualan.sampah'])->findOrFail($id);

        $pdf = \PDF::loadView('layout.pdf_penjualan', compact('penjualan'));
        return $pdf->stream('Invoice_Penjualan_'.$penjualan->id_penjualan.'.pdf');
    }
}

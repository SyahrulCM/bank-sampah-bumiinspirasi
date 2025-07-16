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

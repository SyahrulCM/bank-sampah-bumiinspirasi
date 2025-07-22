<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\File;

class PengumumanApiController extends Controller
{
    public function apiIndex()
    {
        $pengumuman = Pengumuman::all();

        return response()->json([
            'status' => true,
            'message' => 'Data pengumuman berhasil diambil',
            'data' => $pengumuman
        ]);
    }
}
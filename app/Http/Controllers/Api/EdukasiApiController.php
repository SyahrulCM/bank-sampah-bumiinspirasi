<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Edukasi;
use Illuminate\Support\Facades\File;

class EdukasiApiController extends Controller
{
    public function apiEdukasi()
    {
        $data = Edukasi::orderBy('id_edukasi', 'asc')->get();

        foreach ($data as $item) {
            $item->foto_url = $item->foto ? url($item->foto) : null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Data edukasi berhasil diambil.',
            'data' => $data
        ]);
    }
}
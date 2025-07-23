<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    use HasFactory;

    protected $table = 'penarikans';

    protected $fillable = [
        'id_registrasi',
        'jumlah',
        'tanggal',
        'keterangan',
        'status',
        'alasan_ditolak',
    ];

    // Relasi ke model Registrasi
    public function registrasi()
    {
        return $this->belongsTo(Registrasi::class, 'id_registrasi');
    }
}

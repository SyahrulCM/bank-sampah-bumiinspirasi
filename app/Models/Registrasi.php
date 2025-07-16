<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_registrasi';
    protected $table = 'registrasis';
    protected $fillable = [
        'id_registrasi',
        'nama_lengkap',
        'usia',
        'jenis_kelamin',
        'alamat',
        'nomer_telepon',
        'email',
        'pekerjaan',
        'nama_rekening',
        'nomor_rekening',
        'transportasi',
        'mengetahui',
        'alasan',
        'tanggal'
    ];

}

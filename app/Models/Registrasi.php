<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;

class Registrasi extends Model
{
    use HasApiTokens;

    protected $primaryKey = 'id_registrasi';

    protected $fillable = [
        'nama_lengkap', 'alamat', 'nomer_telepon', 'nomer_induk_nasabah', 'password', 'tanggal'
    ];

    protected $hidden = ['password'];
}

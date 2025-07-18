<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    use HasFactory;
    protected $table = 'riwayats';
    protected $fillable = ['id_riwayat','tanggal','id_sampah','berat_sampah','jumlah'];
}

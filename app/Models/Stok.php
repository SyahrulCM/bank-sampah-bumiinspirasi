<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_stok';
    protected $table = 'stoks';
    protected $fillable = ['id_stok','id_sampah','jumlah','tanggal'];
    public $timestamps = true;

    public function sampah()
    {
        return $this->belongsTo(Sampah::class, 'id_sampah');
    }
    
}

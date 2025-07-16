<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrasis', function (Blueprint $table) {
            $table->increments('id_registrasi');
            $table->string('nama_lengkap');
            $table->integer('usia');
            $table->enum('jenis_kelamin', ['Pria','Wanita']);
            $table->text('alamat');
            $table->string('nomer_telepon');
            $table->string('email');
            $table->text('pekerjaan');
            $table->string('nama_rekening');
            $table->string('nomor_rekening');
            $table->enum('transportasi', ['Jalan','Motor','Mobil']);
            $table->enum('mengetahui',['Keluarga','Tetangga','Website','Petugas BSBI','Tahu Sendiri','Rekanan Kegiatan','Lainnya']);
            $table->text('alasan');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasis');
    }
};

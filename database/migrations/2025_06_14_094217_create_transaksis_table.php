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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->date('tanggal');
            // $table->unsignedInteger('id_petugas');
            // $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
            $table->unsignedInteger('id_registrasi');
            $table->foreign('id_registrasi')->references('id_registrasi')->on('registrasis')->onDelete('cascade');
            // $table->unsignedInteger('id_sampah');
            // $table->foreign('id_sampah')->references('id_sampah')->on('sampahs')->onDelete('cascade');
            // $table->float('berat_sampah');
            $table->integer('saldo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

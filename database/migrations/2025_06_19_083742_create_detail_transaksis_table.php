<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->increments('id_detail_transaksi');
            $table->unsignedInteger('id_transaksi');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis')->onDelete('cascade');
            $table->unsignedInteger('id_sampah');
            $table->foreign('id_sampah')->references('id_sampah')->on('sampahs')->onDelete('cascade');
            $table->float('berat_sampah');
            $table->date('tanggal');
            $table->unsignedInteger('jumlah_setoran')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};

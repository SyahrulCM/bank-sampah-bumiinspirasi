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
        Schema::create('mutasis', function (Blueprint $table) {
            $table->increments('id_mutasi');
            $table->date('tanggal');
            $table->unsignedInteger('id_sampah');
            $table->foreign('id_sampah')->references('id_sampah')->on('sampahs')->onDelete('cascade');
            $table->enum('aksi', ['Masuk','Keluar']);
            $table->float('berat');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};

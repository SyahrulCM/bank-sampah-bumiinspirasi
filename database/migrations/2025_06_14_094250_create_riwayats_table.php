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
        Schema::create('riwayats', function (Blueprint $table) {
            $table->increments('id_riwayat');
            $table->date('tanggal');
            $table->unsignedInteger('id_sampah');
            $table->foreign('id_sampah')->references('id_sampah')->on('sampahs')->onDelete('cascade');
            $table->float('berat_sampah');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};

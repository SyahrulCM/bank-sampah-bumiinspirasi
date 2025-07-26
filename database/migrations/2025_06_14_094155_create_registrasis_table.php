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
            $table->text('alamat');
            $table->string('nomer_telepon');
            $table->string('nomer_induk_nasabah')->nullable();
            $table->string('password');
            $table->date('tanggal');
            $table->bigInteger('saldo')->nullable();
            $table->string('foto')->nullable();
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

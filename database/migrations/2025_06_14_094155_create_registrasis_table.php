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
            $table->text('alamat')->nullable();
            $table->string('nomer_telepon')->nullable();
            $table->string('nomer_induk_nasabah')->nullable();
            $table->string('password')->nullable();
            $table->date('tanggal');
            $table->decimal('saldo', 15, 2)->nullable();
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

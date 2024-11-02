<?php

use Carbon\Carbon;
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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_registrasi');
            $table->string('fullname');
            $table->string('NISN');
            $table->string('NIS');
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('agama');
            $table->string('anak_ke');
            $table->string('jumlah_saudara');
            $table->string('no_hp');
            $table->string('email');
            $table->string('photo')->nullable();
            $table->enum('status', ['belum di Proses', 'di terima', 'tidak di terima'])->default('belum di Proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

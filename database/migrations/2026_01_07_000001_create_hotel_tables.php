<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengunjung
        Schema::create('pengunjung', function (Blueprint $table) {
            $table->string('id_pengunjung', 10)->primary();
            $table->string('nm_pengunjung', 100);
            $table->text('alamat')->nullable();
            $table->enum('jk', ['L', 'P']);
            $table->string('no_tlp', 20);
            $table->string('no_ktp', 20);
            $table->timestamps();
        });

        // Karyawan
        Schema::create('karyawan', function (Blueprint $table) {
            $table->string('id_karyawan', 10)->primary();
            $table->string('nm_karyawan', 100);
            $table->enum('jk', ['L', 'P']);
            $table->string('password');
            $table->timestamps();
        });

        // Kamar
        Schema::create('kamar', function (Blueprint $table) {
            $table->integer('no_kamar')->primary();
            $table->string('jenis_kamar', 50);
            $table->bigInteger('harga');
            $table->enum('status', ['tersedia', 'dipesan', 'terisi', 'maintenance'])->default('tersedia');
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Transaksi
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('no_transaksi');
            $table->string('id_pengunjung', 10);
            $table->string('id_karyawan', 10)->nullable();
            $table->integer('jmlh_kamar');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar');
            $table->integer('lama_nginap');
            $table->bigInteger('total_harga');
            $table->enum('status', ['pending', 'dikonfirmasi', 'dibayar', 'selesai', 'batal'])->default('pending');
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();

            $table->foreign('id_pengunjung')->references('id_pengunjung')->on('pengunjung')->onDelete('cascade');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('set null');
        });

        // Detail Transaksi
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id_dtl_transaksi');
            $table->unsignedBigInteger('no_transaksi');
            $table->integer('no_kamar');
            $table->timestamps();

            $table->foreign('no_transaksi')->references('no_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('no_kamar')->references('no_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('kamar');
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('pengunjung');
    }
};

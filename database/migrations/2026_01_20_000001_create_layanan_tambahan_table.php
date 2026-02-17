<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel master layanan tambahan
        Schema::create('layanan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 100);
            $table->text('deskripsi')->nullable();
            $table->bigInteger('harga');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // Tabel pivot untuk transaksi - layanan
        Schema::create('transaksi_layanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('no_transaksi');
            $table->unsignedBigInteger('layanan_id');
            $table->integer('jumlah')->default(1);
            $table->bigInteger('subtotal');
            $table->timestamps();

            $table->foreign('no_transaksi')->references('no_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('layanan_id')->references('id')->on('layanan_tambahan')->onDelete('cascade');
        });

        // Insert default layanan
        DB::table('layanan_tambahan')->insert([
            ['nama_layanan' => 'Extra Bed', 'deskripsi' => 'Kasur tambahan untuk tamu', 'harga' => 150000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Extra Pillow', 'deskripsi' => 'Bantal tambahan', 'harga' => 25000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Extra Blanket', 'deskripsi' => 'Selimut tambahan', 'harga' => 35000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Baby Crib', 'deskripsi' => 'Box bayi', 'harga' => 100000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Airport Transfer', 'deskripsi' => 'Antar jemput bandara', 'harga' => 250000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Breakfast', 'deskripsi' => 'Sarapan pagi per orang', 'harga' => 75000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Laundry Service', 'deskripsi' => 'Layanan laundry per kg', 'harga' => 50000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_layanan' => 'Late Check-out', 'deskripsi' => 'Perpanjangan waktu check-out hingga jam 18:00', 'harga' => 200000, 'aktif' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_layanan');
        Schema::dropIfExists('layanan_tambahan');
    }
};

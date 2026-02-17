<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;
use App\Models\Kamar;
use App\Models\Pengunjung;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Karyawan::create([
            'id_karyawan' => 'KRY001',
            'nm_karyawan' => 'Admin Hotel',
            'jk' => 'L',
            'password' => Hash::make('admin123'),
        ]);

        Karyawan::create([
            'id_karyawan' => 'KRY002',
            'nm_karyawan' => 'Siti Receptionist',
            'jk' => 'P',
            'password' => Hash::make('siti123'),
        ]);

        $kamarData = [
            ['no_kamar' => 101, 'jenis_kamar' => 'Standard', 'harga' => 500000, 'status' => 'tersedia'],
            ['no_kamar' => 102, 'jenis_kamar' => 'Standard', 'harga' => 500000, 'status' => 'tersedia'],
            ['no_kamar' => 103, 'jenis_kamar' => 'Standard', 'harga' => 500000, 'status' => 'tersedia'],
            ['no_kamar' => 201, 'jenis_kamar' => 'Deluxe', 'harga' => 850000, 'status' => 'tersedia'],
            ['no_kamar' => 202, 'jenis_kamar' => 'Deluxe', 'harga' => 850000, 'status' => 'tersedia'],
            ['no_kamar' => 203, 'jenis_kamar' => 'Deluxe', 'harga' => 850000, 'status' => 'tersedia'],
            ['no_kamar' => 301, 'jenis_kamar' => 'Suite', 'harga' => 1500000, 'status' => 'tersedia'],
            ['no_kamar' => 302, 'jenis_kamar' => 'Suite', 'harga' => 1500000, 'status' => 'tersedia'],
            ['no_kamar' => 401, 'jenis_kamar' => 'Presidential Suite', 'harga' => 3500000, 'status' => 'tersedia'],
        ];

        foreach ($kamarData as $kamar) {
            Kamar::create($kamar);
        }

        Pengunjung::create([
            'id_pengunjung' => 'PGJ001',
            'nm_pengunjung' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta',
            'jk' => 'L',
            'no_tlp' => '081234567890',
            'no_ktp' => '3171234567890001',
        ]);

        Pengunjung::create([
            'id_pengunjung' => 'PGJ002',
            'nm_pengunjung' => 'Dewi Lestari',
            'alamat' => 'Jl. Sudirman No. 45, Bandung',
            'jk' => 'P',
            'no_tlp' => '082345678901',
            'no_ktp' => '3171234567890002',
        ]);
    }
}

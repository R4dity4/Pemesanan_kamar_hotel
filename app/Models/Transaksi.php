<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'no_transaksi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pengunjung', 'id_karyawan', 'jmlh_kamar', 'tgl_masuk', 'tgl_keluar',
        'lama_nginap', 'total_harga', 'status', 'bukti_bayar'
    ];

    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class, 'id_pengunjung', 'id_pengunjung');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'no_transaksi', 'no_transaksi');
    }

    public function transaksiLayanan()
    {
        return $this->hasMany(TransaksiLayanan::class, 'no_transaksi', 'no_transaksi');
    }
}

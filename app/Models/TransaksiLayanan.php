<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiLayanan extends Model
{
    protected $table = 'transaksi_layanan';

    protected $fillable = [
        'no_transaksi',
        'layanan_id',
        'jumlah',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'no_transaksi', 'no_transaksi');
    }

    public function layanan()
    {
        return $this->belongsTo(LayananTambahan::class, 'layanan_id');
    }

    // Alias for layanan
    public function layananTambahan()
    {
        return $this->belongsTo(LayananTambahan::class, 'layanan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_dtl_transaksi';
    public $incrementing = true;

    protected $fillable = [
        'no_transaksi', 'no_kamar'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'no_transaksi', 'no_transaksi');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'no_kamar', 'no_kamar');
    }
}

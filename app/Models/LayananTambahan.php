<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananTambahan extends Model
{
    protected $table = 'layanan_tambahan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'harga',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function transaksiLayanan()
    {
        return $this->hasMany(TransaksiLayanan::class, 'layanan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'no_kamar';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'no_kamar', 'jenis_kamar', 'harga', 'status', 'gambar', 'deskripsi'
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'no_kamar', 'no_kamar');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    protected $table = 'pengunjung';
    protected $primaryKey = 'id_pengunjung';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengunjung', 'nm_pengunjung', 'alamat', 'jk', 'no_tlp', 'email', 'no_ktp'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pengunjung', 'id_pengunjung');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_karyawan', 'nm_karyawan', 'jk', 'password'
    ];

    protected $hidden = ['password'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_karyawan', 'id_karyawan');
    }
}

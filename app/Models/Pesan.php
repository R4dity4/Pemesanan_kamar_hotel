<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesans';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'topik',
        'pesan',
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index()
    {
        return view('pengunjung.aktivitas');
    }
}

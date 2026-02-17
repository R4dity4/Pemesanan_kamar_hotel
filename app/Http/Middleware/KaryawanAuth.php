<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KaryawanAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('karyawan')) {
            return redirect('/admin/login')->withErrors(['auth' => 'Silakan login terlebih dahulu']);
        }
        return $next($request);
    }
}

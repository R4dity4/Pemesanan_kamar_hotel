<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('karyawan')) {
            return redirect('/admin');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required',
            'password' => 'required',
        ]);

        $karyawan = Karyawan::find($request->id_karyawan);

        if ($karyawan && Hash::check($request->password, $karyawan->password)) {
            session(['karyawan' => $karyawan]);
            return redirect('/admin')->with('success', 'Login berhasil');
        }

        return back()->withErrors(['login' => 'ID Karyawan atau Password salah']);
    }

    public function logout()
    {
        session()->forget('karyawan');
        return redirect('/admin/login')->with('success', 'Logout berhasil');
    }
}

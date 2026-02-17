<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarAdminController extends Controller
{
    public function index()
    {
        $kamars = Kamar::orderBy('no_kamar')->get();
        return view('admin.kamar.index', compact('kamars'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kamar' => 'required|integer|unique:kamar,no_kamar',
            'jenis_kamar' => 'required|string|max:20',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,terisi,maintenance',
        ]);

        Kamar::create($request->only('no_kamar', 'jenis_kamar', 'harga', 'status'));

        return redirect('/admin/kamar')->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit($no_kamar)
    {
        $kamar = Kamar::findOrFail($no_kamar);
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, $no_kamar)
    {
        $request->validate([
            'jenis_kamar' => 'required|string|max:20',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,terisi,maintenance',
        ]);

        $kamar = Kamar::findOrFail($no_kamar);
        $kamar->update($request->only('jenis_kamar', 'harga', 'status'));

        return redirect('/admin/kamar')->with('success', 'Kamar berhasil diupdate');
    }

    public function destroy($no_kamar)
    {
        $kamar = Kamar::findOrFail($no_kamar);
        $kamar->delete();

        return redirect('/admin/kamar')->with('success', 'Kamar berhasil dihapus');
    }
}

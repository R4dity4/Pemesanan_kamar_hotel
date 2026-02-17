<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesan;

class KontakController extends Controller
{
    public function index()
    {
        return view('pengunjung.kontak');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'topic' => 'required|string',
            'message' => 'required|string',
        ]);

        Pesan::create([
            'nama' => $request->name,
            'email' => $request->email,
            'telepon' => $request->phone,
            'topik' => $request->topic,
            'pesan' => $request->message,
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim! Kami akan segera kami respons.');
    }
}

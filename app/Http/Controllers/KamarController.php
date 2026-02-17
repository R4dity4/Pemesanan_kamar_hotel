<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $query = Kamar::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('jenis_kamar', 'like', '%' . $search . '%')
                  ->orWhere('no_kamar', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        // Filter by jenis kamar
        if ($request->filled('jenis')) {
            $query->where('jenis_kamar', $request->jenis);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by harga
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Get unique jenis kamar for filter dropdown
        $jenisKamarList = Kamar::select('jenis_kamar')->distinct()->pluck('jenis_kamar');

        // Pagination
        $kamars = $query->orderBy('no_kamar')->paginate(6)->withQueryString();

        return view('pengunjung.kamar', compact('kamars', 'jenisKamarList'));
    }

    public function show($no_kamar)
    {
        $kamar = Kamar::findOrFail($no_kamar);
        $kamarLain = Kamar::where('no_kamar', '!=', $no_kamar)
            ->where('status', 'tersedia')
            ->take(3)
            ->get();
        return view('pengunjung.kamar-detail', compact('kamar', 'kamarLain'));
    }
}

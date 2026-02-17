<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use Illuminate\Http\Request;

class PesanAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesan::query();

        // Filter by read status
        if ($request->status === 'belum') {
            $query->where('dibaca', false);
        } elseif ($request->status === 'sudah') {
            $query->where('dibaca', true);
        }

        // Filter by topic
        if ($request->topik) {
            $query->where('topik', $request->topik);
        }

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('pesan', 'like', "%{$search}%");
            });
        }

        $pesans = $query->latest()->paginate(15);

        // Stats
        $stats = [
            'total' => Pesan::count(),
            'belum_dibaca' => Pesan::where('dibaca', false)->count(),
            'sudah_dibaca' => Pesan::where('dibaca', true)->count(),
        ];

        return view('admin.pesan.index', compact('pesans', 'stats'));
    }

    public function show($id)
    {
        $pesan = Pesan::findOrFail($id);
        
        // Mark as read
        if (!$pesan->dibaca) {
            $pesan->update(['dibaca' => true]);
        }

        return view('admin.pesan.show', compact('pesan'));
    }

    public function destroy($id)
    {
        $pesan = Pesan::findOrFail($id);
        $pesan->delete();

        return redirect('/admin/pesan')->with('success', 'Pesan berhasil dihapus');
    }

    public function markAsRead($id)
    {
        $pesan = Pesan::findOrFail($id);
        $pesan->update(['dibaca' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca');
    }

    public function markAllAsRead()
    {
        Pesan::where('dibaca', false)->update(['dibaca' => true]);

        return back()->with('success', 'Semua pesan ditandai sudah dibaca');
    }
}

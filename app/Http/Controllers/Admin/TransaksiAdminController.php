<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pengunjung', 'karyawan']);

        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('tgl_masuk', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tgl_masuk', '<=', $request->end_date);
        }

        // Search by transaction number or guest name
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('pengunjung', function($q2) use ($search) {
                      $q2->where('nm_pengunjung', 'like', "%{$search}%");
                  });
            });
        }

        $transaksis = $query->latest()->get();

        // Calculate stats
        $stats = [
            'total' => Transaksi::count(),
            'pending' => Transaksi::where('status', 'pending')->count(),
            'dikonfirmasi' => Transaksi::where('status', 'dikonfirmasi')->count(),
            'dibayar' => Transaksi::where('status', 'dibayar')->count(),
            'selesai' => Transaksi::where('status', 'selesai')->count(),
            'batal' => Transaksi::where('status', 'batal')->count(),
            'revenue_month' => Transaksi::whereIn('status', ['dibayar', 'selesai'])
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_harga'),
        ];

        return view('admin.transaksi.index', compact('transaksis', 'stats'));
    }

    public function show($no_transaksi)
    {
        $transaksi = Transaksi::with(['pengunjung', 'karyawan', 'detailTransaksi.kamar', 'transaksiLayanan.layananTambahan'])
            ->findOrFail($no_transaksi);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function konfirmasiPesanan($no_transaksi)
    {
        $transaksi = Transaksi::with('pengunjung')->findOrFail($no_transaksi);
        $transaksi->update([
            'status' => 'dikonfirmasi',
            'id_karyawan' => session('karyawan')->id_karyawan
        ]);

        return redirect('/admin/transaksi')->with('success', 'Pesanan berhasil dikonfirmasi');
    }

    public function konfirmasiPembayaran($no_transaksi)
    {
        $transaksi = Transaksi::with('pengunjung')->findOrFail($no_transaksi);
        $transaksi->update([
            'status' => 'dibayar',
            'id_karyawan' => session('karyawan')->id_karyawan
        ]);

        return redirect('/admin/transaksi')->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function selesai($no_transaksi)
    {
        $transaksi = Transaksi::with('pengunjung')->findOrFail($no_transaksi);
        $transaksi->update(['status' => 'selesai']);

        return redirect('/admin/transaksi')->with('success', 'Transaksi selesai');
    }

    public function batal($no_transaksi)
    {
        $transaksi = Transaksi::with('pengunjung')->findOrFail($no_transaksi);
        $transaksi->update(['status' => 'batal']);

        return redirect('/admin/transaksi')->with('success', 'Transaksi dibatalkan');
    }

    /**
     * Export transaksi to CSV
     */
    public function export(Request $request)
    {
        $query = Transaksi::with(['pengunjung', 'karyawan']);

        // Apply same filters as index
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->start_date) {
            $query->whereDate('tgl_masuk', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tgl_masuk', '<=', $request->end_date);
        }

        $transaksis = $query->latest()->get();

        $filename = 'transaksi_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($transaksis) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'No Transaksi', 'Pengunjung', 'Telepon', 'Email',
                'Check-in', 'Check-out', 'Lama Nginap',
                'Jumlah Kamar', 'Total Harga', 'Status', 'Tanggal Pesan'
            ]);

            // Data
            foreach ($transaksis as $t) {
                fputcsv($file, [
                    $t->no_transaksi,
                    $t->pengunjung->nm_pengunjung ?? '-',
                    $t->pengunjung->no_tlp ?? '-',
                    $t->pengunjung->email ?? '-',
                    $t->tgl_masuk,
                    $t->tgl_keluar,
                    $t->lama_nginap,
                    $t->jmlh_kamar,
                    $t->total_harga,
                    $t->status,
                    $t->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

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

        $semuaKamar = \App\Models\Kamar::orderBy('no_kamar')->get();
        $semuaLayanan = \App\Models\LayananTambahan::all();

        return view('admin.transaksi.show', compact('transaksi', 'semuaKamar', 'semuaLayanan'));
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

    public function tolakBukti($no_transaksi)
    {
        $transaksi = Transaksi::findOrFail($no_transaksi);

        // Hapus file fisik jika ada
        if ($transaksi->bukti_bayar && \Illuminate\Support\Facades\Storage::disk('public')->exists($transaksi->bukti_bayar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($transaksi->bukti_bayar);
        }

        // Reset bukti_bayar jadi null, status tetap dikonfirmasi
        $transaksi->update([
            'bukti_bayar' => null
        ]);

        return redirect()->back()->with('error', 'Bukti pembayaran ditolak karena tidak valid. Pengunjung harus mengupload ulang.');
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

    public function updateDetail(Request $request, $no_transaksi)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
            'tgl_keluar' => 'required|date|after:tgl_masuk',
            'kamar_ids' => 'required|array|min:1',
        ]);

        $transaksi = Transaksi::with(['detailTransaksi.kamar', 'transaksiLayanan'])->findOrFail($no_transaksi);

        $tglMasuk = \Carbon\Carbon::parse($request->tgl_masuk);
        $tglKeluar = \Carbon\Carbon::parse($request->tgl_keluar);
        $lamaNginap = $tglMasuk->diffInDays($tglKeluar);

        if ($lamaNginap == 0) {
            $lamaNginap = 1;
        }

        // Cek ketersediaan kamar (kecualikan transaksi ini sendiri)
        $bookedKamars = \App\Models\DetailTransaksi::whereHas('transaksi', function($query) use ($tglMasuk, $tglKeluar, $no_transaksi) {
            $query->where('no_transaksi', '!=', $no_transaksi)
                  ->whereNotIn('status', ['batal', 'selesai'])
                  ->where('tgl_masuk', '<', $tglKeluar)
                  ->where('tgl_keluar', '>', $tglMasuk);
        })->pluck('no_kamar')->toArray();

        foreach ($request->kamar_ids as $kamar_id) {
            if (in_array($kamar_id, $bookedKamars)) {
                return redirect()->back()->with('error', "Update gagal: Kamar nomor $kamar_id sudah dipesan oleh pengunjung lain pada tanggal tersebut.");
            }
        }

        // Hitung harga total baru
        $totalHargaKamarBaru = 0;
        foreach ($request->kamar_ids as $kamar_id) {
            $kamar = \App\Models\Kamar::find($kamar_id);
            if ($kamar) {
                $totalHargaKamarBaru += $kamar->harga * $lamaNginap;
            }
        }

        // Hitung total layanan baru dan siapkan data untuk disimpan
        $totalLayananMurni = 0;
        $layananDataBaru = [];

        if ($request->has('layanan') && is_array($request->layanan)) {
            foreach ($request->layanan as $layanan_id => $jumlah) {
                if ($jumlah > 0) {
                    $layananModel = \App\Models\LayananTambahan::find($layanan_id);
                    if ($layananModel) {
                        $subtotal = $layananModel->harga * $jumlah;
                        $totalLayananMurni += $subtotal;

                        $layananDataBaru[] = [
                            'no_transaksi' => $no_transaksi,
                            'layanan_id' => $layanan_id,
                            'jumlah' => $jumlah,
                            'subtotal' => $subtotal
                        ];
                    }
                }
            }
        }

        $totalHargaBaru = $totalHargaKamarBaru + $totalLayananMurni;

        // Update relasi kamar
        \App\Models\DetailTransaksi::where('no_transaksi', $no_transaksi)->delete();
        foreach ($request->kamar_ids as $kamar_id) {
            \App\Models\DetailTransaksi::create([
                'no_transaksi' => $no_transaksi,
                'no_kamar' => $kamar_id
            ]);
        }

        // Update relasi layanan
        \App\Models\TransaksiLayanan::where('no_transaksi', $no_transaksi)->delete();
        foreach ($layananDataBaru as $data) {
            \App\Models\TransaksiLayanan::create($data);
        }

        // Update header transaksi
        $transaksi->update([
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_keluar' => $request->tgl_keluar,
            'jmlh_kamar' => count($request->kamar_ids),
            'lama_nginap' => $lamaNginap,
            'total_harga' => $totalHargaBaru
        ]);

        return redirect()->back()->with('success', 'Detail tanggal dan kamar berhasil diperbarui');
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

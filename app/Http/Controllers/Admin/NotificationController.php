<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pesan;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Check for new notifications (polled by admin panel).
     */
    public function check(Request $request)
    {
        $lastCheck = $request->input('last_check');
        $isFirstCheck = $request->boolean('first', false);

        // Total pending (selalu dikirim untuk badge)
        $totalPending = Transaksi::where('status', 'pending')->count();

        // New pending since last check
        $newPending = 0;
        $latestTransaksi = null;

        if ($lastCheck) {
            $newPending = Transaksi::where('status', 'pending')
                ->where('created_at', '>', $lastCheck)
                ->count();

            if ($newPending > 0) {
                $latestTransaksi = Transaksi::with('pengunjung')
                    ->where('status', 'pending')
                    ->where('created_at', '>', $lastCheck)
                    ->latest()
                    ->first();
            }
        } elseif ($isFirstCheck) {
            // First check: report existing pendings as initial data (no toast)
            $newPending = 0; // Don't trigger toast on first load
        }

        // Pending yang butuh konfirmasi pembayaran
        $pendingBayar = Transaksi::where('status', 'dibayar')
            ->whereNotNull('bukti_bayar')
            ->count();

        $newBukti = 0;
        if ($lastCheck) {
            $newBukti = Transaksi::where('status', 'dibayar')
                ->whereNotNull('bukti_bayar')
                ->where('updated_at', '>', $lastCheck)
                ->count();
        }

        // Unread pesan
        $unreadPesan = Pesan::where('dibaca', false)->count();

        // New pesan since last check
        $newPesan = 0;
        $latestPesan = null;
        if ($lastCheck) {
            $newPesan = Pesan::where('created_at', '>', $lastCheck)->count();
            if ($newPesan > 0) {
                $latestPesan = Pesan::where('created_at', '>', $lastCheck)->latest()->first();
            }
        }

        // Hash data transaksi untuk deteksi perubahan di halaman transaksi
        $transaksiHash = md5(
            Transaksi::selectRaw('COUNT(*) as c, MAX(updated_at) as u')
                ->first()
                ->toJson()
        );

        // Hash data pesan untuk deteksi perubahan di halaman pesan
        $pesanHash = md5(
            Pesan::selectRaw('COUNT(*) as c, MAX(COALESCE(updated_at, created_at)) as u')
                ->first()
                ->toJson()
        );

        return response()->json([
            'new_pending' => $newPending,
            'total_pending' => $totalPending,
            'pending_bayar' => $pendingBayar,
            'new_bukti' => $newBukti,
            'unread_pesan' => $unreadPesan,
            'new_pesan' => $newPesan,
            'transaksi_hash' => $transaksiHash,
            'pesan_hash' => $pesanHash,
            'latest' => $latestTransaksi ? [
                'no_transaksi' => $latestTransaksi->no_transaksi,
                'nama' => $latestTransaksi->pengunjung->nm_pengunjung ?? 'Tamu',
                'total' => number_format($latestTransaksi->total_harga, 0, ',', '.'),
                'waktu' => $latestTransaksi->created_at->diffForHumans(),
            ] : null,
            'latest_pesan' => $latestPesan ? [
                'id' => $latestPesan->id,
                'nama' => $latestPesan->nama,
                'topik' => ucfirst($latestPesan->topik),
                'preview' => \Illuminate\Support\Str::limit($latestPesan->pesan, 60),
                'waktu' => $latestPesan->created_at->diffForHumans(),
            ] : null,
            'server_time' => now()->toIso8601String(),
        ]);
    }
}

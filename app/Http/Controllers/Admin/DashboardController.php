<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kamar;
use App\Models\Pengunjung;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalKamar = Kamar::count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $totalTransaksi = Transaksi::count();
        $transaksiPending = Transaksi::where('status', 'pending')->count();
        $totalPengunjung = Pengunjung::count();

        // Revenue stats
        $revenueMonth = Transaksi::whereIn('status', ['dibayar', 'selesai'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');

        $revenueLastMonth = Transaksi::whereIn('status', ['dibayar', 'selesai'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_harga');

        $revenueGrowth = $revenueLastMonth > 0 
            ? round((($revenueMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        // Transaction stats by status
        $statusStats = [
            'pending' => Transaksi::where('status', 'pending')->count(),
            'dikonfirmasi' => Transaksi::where('status', 'dikonfirmasi')->count(),
            'dibayar' => Transaksi::where('status', 'dibayar')->count(),
            'selesai' => Transaksi::where('status', 'selesai')->count(),
            'batal' => Transaksi::where('status', 'batal')->count(),
        ];

        // Chart: Monthly revenue for last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaksi::whereIn('status', ['dibayar', 'selesai'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_harga');
            
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }

        // Chart: Daily bookings for last 7 days
        $dailyBookings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Transaksi::whereDate('created_at', $date->format('Y-m-d'))->count();
            
            $dailyBookings[] = [
                'day' => $date->format('D'),
                'date' => $date->format('d/m'),
                'count' => $count,
            ];
        }

        // Room type distribution
        $roomTypes = Kamar::select('jenis_kamar', DB::raw('count(*) as total'))
            ->groupBy('jenis_kamar')
            ->get();

        // Today's check-ins and check-outs
        $todayCheckIns = Transaksi::with('pengunjung')
            ->whereDate('tgl_masuk', today())
            ->whereIn('status', ['dikonfirmasi', 'dibayar'])
            ->get();

        $todayCheckOuts = Transaksi::with('pengunjung')
            ->whereDate('tgl_keluar', today())
            ->whereIn('status', ['dibayar'])
            ->get();

        // Recent transactions
        $recentTransaksi = Transaksi::with('pengunjung')->latest()->take(5)->get();

        // Upcoming reservations (next 7 days)
        $upcomingReservations = Transaksi::with('pengunjung')
            ->whereDate('tgl_masuk', '>=', today())
            ->whereDate('tgl_masuk', '<=', today()->addDays(7))
            ->whereNotIn('status', ['batal', 'selesai'])
            ->orderBy('tgl_masuk')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKamar', 'kamarTersedia', 'totalTransaksi', 'transaksiPending',
            'totalPengunjung', 'revenueMonth', 'revenueGrowth', 'statusStats',
            'monthlyRevenue', 'dailyBookings', 'roomTypes',
            'todayCheckIns', 'todayCheckOuts', 'recentTransaksi', 'upcomingReservations'
        ));
    }
}

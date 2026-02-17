<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Kamar;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->periode ?? 'bulanan';
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        $tanggal = $request->tanggal ?? date('Y-m-d');

        // Get available years for dropdown
        $years = Transaksi::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        if (empty($years)) {
            $years = [date('Y')];
        }

        // Get data based on period
        $data = $this->getReportData($periode, $tahun, $bulan, $tanggal);

        return view('admin.laporan.index', compact('periode', 'tahun', 'bulan', 'tanggal', 'years', 'data'));
    }

    public function download(Request $request)
    {
        $periode = $request->periode ?? 'bulanan';
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $data = $this->getReportData($periode, $tahun, $bulan, $tanggal);

        // Generate filename
        switch ($periode) {
            case 'harian':
                $filename = 'Laporan_Harian_' . $tanggal;
                $judulPeriode = 'Harian: ' . Carbon::parse($tanggal)->format('d F Y');
                break;
            case 'bulanan':
                $filename = 'Laporan_Bulanan_' . $tahun . '_' . $bulan;
                $judulPeriode = 'Bulanan: ' . Carbon::create($tahun, $bulan, 1)->format('F Y');
                break;
            case 'tahunan':
                $filename = 'Laporan_Tahunan_' . $tahun;
                $judulPeriode = 'Tahunan: ' . $tahun;
                break;
            default:
                $filename = 'Laporan';
                $judulPeriode = '';
        }

        $pdf = Pdf::loadView('admin.laporan.pdf-new', [
            'data' => $data,
            'periode' => $periode,
            'judulPeriode' => $judulPeriode,
            'tanggalCetak' => Carbon::now()->format('d F Y H:i'),
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download($filename . '.pdf');
    }

    private function getReportData($periode, $tahun, $bulan, $tanggal)
    {
        $query = Transaksi::with(['pengunjung', 'detailTransaksi.kamar']);

        switch ($periode) {
            case 'harian':
                $query->whereDate('created_at', $tanggal);
                $periodLabel = Carbon::parse($tanggal)->format('d F Y');
                break;
            case 'bulanan':
                $query->whereYear('created_at', $tahun)
                      ->whereMonth('created_at', $bulan);
                $periodLabel = Carbon::create($tahun, $bulan, 1)->format('F Y');
                break;
            case 'tahunan':
                $query->whereYear('created_at', $tahun);
                $periodLabel = $tahun;
                break;
            default:
                $periodLabel = '';
        }

        $transaksis = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $totalTransaksi = $transaksis->count();
        $totalPending = $transaksis->where('status', 'pending')->count();
        $totalDikonfirmasi = $transaksis->where('status', 'dikonfirmasi')->count();
        $totalDibayar = $transaksis->where('status', 'dibayar')->count();
        $totalSelesai = $transaksis->where('status', 'selesai')->count();
        $totalBatal = $transaksis->where('status', 'batal')->count();

        // Revenue (only from paid/completed)
        $totalRevenue = $transaksis->whereIn('status', ['dibayar', 'selesai'])->sum('total_harga');
        $potentialRevenue = $transaksis->whereIn('status', ['pending', 'dikonfirmasi'])->sum('total_harga');

        // Room stats
        $totalKamarDipesan = $transaksis->sum('jmlh_kamar');
        $avgLamaNginap = $transaksis->avg('lama_nginap') ?? 0;

        // Top rooms
        $roomStats = [];
        foreach ($transaksis as $t) {
            foreach ($t->detailTransaksi as $detail) {
                $noKamar = $detail->no_kamar;
                if (!isset($roomStats[$noKamar])) {
                    $roomStats[$noKamar] = [
                        'no_kamar' => $noKamar,
                        'jenis' => $detail->kamar->jenis_kamar ?? '-',
                        'count' => 0,
                        'revenue' => 0,
                    ];
                }
                $roomStats[$noKamar]['count']++;
                if (in_array($t->status, ['dibayar', 'selesai'])) {
                    $roomStats[$noKamar]['revenue'] += $detail->kamar->harga * $t->lama_nginap;
                }
            }
        }
        usort($roomStats, fn($a, $b) => $b['count'] - $a['count']);
        $topRooms = array_slice($roomStats, 0, 5);

        // Monthly breakdown for yearly report
        $monthlyData = [];
        if ($periode === 'tahunan') {
            for ($m = 1; $m <= 12; $m++) {
                $monthTransaksi = $transaksis->filter(function ($t) use ($m) {
                    return Carbon::parse($t->created_at)->month === $m;
                });
                $monthlyData[] = [
                    'bulan' => Carbon::create($tahun, $m, 1)->format('M'),
                    'transaksi' => $monthTransaksi->count(),
                    'revenue' => $monthTransaksi->whereIn('status', ['dibayar', 'selesai'])->sum('total_harga'),
                ];
            }
        }

        // Daily breakdown for monthly report
        $dailyData = [];
        if ($periode === 'bulanan') {
            $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayTransaksi = $transaksis->filter(function ($t) use ($d) {
                    return Carbon::parse($t->created_at)->day === $d;
                });
                if ($dayTransaksi->count() > 0) {
                    $dailyData[] = [
                        'tanggal' => $d,
                        'transaksi' => $dayTransaksi->count(),
                        'revenue' => $dayTransaksi->whereIn('status', ['dibayar', 'selesai'])->sum('total_harga'),
                    ];
                }
            }
        }

        return [
            'periodLabel' => $periodLabel,
            'transaksis' => $transaksis,
            'totalTransaksi' => $totalTransaksi,
            'statusStats' => [
                'pending' => $totalPending,
                'dikonfirmasi' => $totalDikonfirmasi,
                'dibayar' => $totalDibayar,
                'selesai' => $totalSelesai,
                'batal' => $totalBatal,
            ],
            'totalRevenue' => $totalRevenue,
            'potentialRevenue' => $potentialRevenue,
            'totalKamarDipesan' => $totalKamarDipesan,
            'avgLamaNginap' => round($avgLamaNginap, 1),
            'topRooms' => $topRooms,
            'monthlyData' => $monthlyData,
            'dailyData' => $dailyData,
        ];
    }
}

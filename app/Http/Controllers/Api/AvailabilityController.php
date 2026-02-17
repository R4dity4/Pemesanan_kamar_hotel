<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    /**
     * Get room availability for a date range
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
            'tgl_keluar' => 'required|date|after:tgl_masuk',
        ]);

        $tglMasuk = Carbon::parse($request->tgl_masuk);
        $tglKeluar = Carbon::parse($request->tgl_keluar);

        // Get all rooms
        $allKamars = Kamar::all();

        // Get rooms that are booked during the selected period
        // A room is unavailable if there's an overlapping booking
        $bookedKamarIds = DetailTransaksi::whereHas('transaksi', function ($query) use ($tglMasuk, $tglKeluar) {
            $query->whereNotIn('status', ['batal', 'selesai'])
                  ->where(function ($q) use ($tglMasuk, $tglKeluar) {
                      // Check for overlapping dates
                      $q->where(function ($inner) use ($tglMasuk, $tglKeluar) {
                          $inner->where('tgl_masuk', '<', $tglKeluar)
                                ->where('tgl_keluar', '>', $tglMasuk);
                      });
                  });
        })->pluck('no_kamar')->unique()->toArray();

        // Build availability response
        $availability = $allKamars->map(function ($kamar) use ($bookedKamarIds) {
            return [
                'no_kamar' => $kamar->no_kamar,
                'jenis_kamar' => $kamar->jenis_kamar,
                'harga' => $kamar->harga,
                'tersedia' => !in_array($kamar->no_kamar, $bookedKamarIds),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $availability,
            'booked_rooms' => $bookedKamarIds,
        ]);
    }

    /**
     * Get calendar data for a specific month
     */
    public function getCalendarData(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get all bookings for this month
        $bookings = Transaksi::with('detailTransaksi.kamar')
            ->whereNotIn('status', ['batal'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tgl_masuk', [$startDate, $endDate])
                      ->orWhereBetween('tgl_keluar', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('tgl_masuk', '<=', $startDate)
                            ->where('tgl_keluar', '>=', $endDate);
                      });
            })
            ->get();

        // Build calendar data
        $calendarData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayBookings = [];

            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->tgl_masuk);
                $bookingEnd = Carbon::parse($booking->tgl_keluar);

                if ($currentDate >= $bookingStart && $currentDate < $bookingEnd) {
                    foreach ($booking->detailTransaksi as $detail) {
                        $dayBookings[] = [
                            'no_kamar' => $detail->no_kamar,
                            'jenis_kamar' => $detail->kamar->jenis_kamar ?? 'Unknown',
                            'pengunjung' => $booking->pengunjung->nm_pengunjung ?? 'Guest',
                            'status' => $booking->status,
                        ];
                    }
                }
            }

            $calendarData[$dateStr] = [
                'date' => $dateStr,
                'day' => $currentDate->day,
                'bookings' => $dayBookings,
                'booked_count' => count($dayBookings),
            ];

            $currentDate->addDay();
        }

        // Get total rooms count
        $totalRooms = Kamar::count();

        return response()->json([
            'success' => true,
            'month' => $month,
            'year' => $year,
            'total_rooms' => $totalRooms,
            'calendar' => $calendarData,
        ]);
    }

    /**
     * Real-time lock check for selected rooms
     */
    public function lockCheck(Request $request)
    {
        $request->validate([
            'kamar' => 'required|array',
            'tgl_masuk' => 'required|date',
            'tgl_keluar' => 'required|date|after:tgl_masuk',
        ]);

        $kamarIds = $request->kamar;
        $tglMasuk = Carbon::parse($request->tgl_masuk);
        $tglKeluar = Carbon::parse($request->tgl_keluar);

        // Check if any of the selected rooms are booked
        $bookedKamars = DetailTransaksi::whereIn('no_kamar', $kamarIds)
            ->whereHas('transaksi', function ($query) use ($tglMasuk, $tglKeluar) {
                $query->whereNotIn('status', ['batal', 'selesai'])
                      ->where('tgl_masuk', '<', $tglKeluar)
                      ->where('tgl_keluar', '>', $tglMasuk);
            })
            ->pluck('no_kamar')
            ->unique()
            ->toArray();

        return response()->json([
            'success' => true,
            'all_available' => count($bookedKamars) === 0,
            'unavailable_rooms' => $bookedKamars,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Pengunjung;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\LayananTambahan;
use App\Models\TransaksiLayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        // Ambil semua kamar untuk ditampilkan
        $kamars = Kamar::orderBy('no_kamar')->get();
        // Ambil layanan tambahan yang aktif
        $layananTambahan = LayananTambahan::where('aktif', true)->get();

        // Build 7-day availability calendar
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(6);

        // Get all bookings overlapping the next 7 days
        $bookings = Transaksi::with('detailTransaksi')
            ->whereNotIn('status', ['batal', 'selesai'])
            ->where('tgl_masuk', '<', $endDate->copy()->addDay()->toDateString())
            ->where('tgl_keluar', '>', $today->toDateString())
            ->get();

        // Build a map: no_kamar => [date => status]
        $bookedMap = [];
        foreach ($bookings as $booking) {
            $bStart = Carbon::parse($booking->tgl_masuk);
            $bEnd = Carbon::parse($booking->tgl_keluar);
            foreach ($booking->detailTransaksi as $detail) {
                $d = $bStart->copy()->lt($today) ? $today->copy() : $bStart->copy();
                $limit = $bEnd->copy()->gt($endDate->copy()->addDay()) ? $endDate->copy()->addDay() : $bEnd->copy();
                while ($d->lt($limit)) {
                    $bookedMap[$detail->no_kamar][$d->format('Y-m-d')] = $booking->status;
                    $d->addDay();
                }
            }
        }

        // Build 7 dates array
        $calendarDates = [];
        for ($i = 0; $i < 7; $i++) {
            $calendarDates[] = $today->copy()->addDays($i);
        }

        return view('pengunjung.reservasi', compact('kamars', 'layananTambahan', 'calendarDates', 'bookedMap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_pengunjung' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jk' => 'required|in:L,P',
            'no_tlp' => 'required|string|max:20',
            'no_ktp' => 'required|string|max:20',
            'tgl_masuk' => 'required|date|after_or_equal:today',
            'tgl_keluar' => 'required|date|after:tgl_masuk',
            'kamar' => 'required|array|min:1',
            'layanan' => 'nullable|array',
            'layanan.*.id' => 'nullable|exists:layanan_tambahan,id',
            'layanan.*.jumlah' => 'nullable|integer|min:0',
        ]);

        $kamarIds = $request->kamar;
        $tglMasuk = Carbon::parse($request->tgl_masuk);
        $tglKeluar = Carbon::parse($request->tgl_keluar);

        // Real-time availability check - Check for date overlap
        $bookedKamarIds = DetailTransaksi::whereIn('no_kamar', $kamarIds)
            ->whereHas('transaksi', function ($query) use ($tglMasuk, $tglKeluar) {
                $query->whereNotIn('status', ['batal', 'selesai'])
                      ->where('tgl_masuk', '<', $tglKeluar)
                      ->where('tgl_keluar', '>', $tglMasuk);
            })
            ->pluck('no_kamar')
            ->unique()
            ->toArray();

        if (count($bookedKamarIds) > 0) {
            return back()->with('error', 'Maaf, kamar ' . implode(', ', $bookedKamarIds) . ' sudah dipesan untuk tanggal tersebut. Silakan pilih kamar lain atau ubah tanggal.')->withInput();
        }

        DB::beginTransaction();
        try {
            // Generate ID Pengunjung
            $lastPengunjung = Pengunjung::orderBy('id_pengunjung', 'desc')->first();
            if ($lastPengunjung) {
                $lastNum = intval(substr($lastPengunjung->id_pengunjung, 3));
                $newIdPengunjung = 'PGJ' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newIdPengunjung = 'PGJ001';
            }

            // Simpan data pengunjung
            $pengunjung = Pengunjung::create([
                'id_pengunjung' => $newIdPengunjung,
                'nm_pengunjung' => $request->nm_pengunjung,
                'alamat' => $request->alamat,
                'jk' => $request->jk,
                'no_tlp' => $request->no_tlp,
                'no_ktp' => $request->no_ktp,
            ]);

            // Hitung total harga dan lama nginap
            $lamaNginap = $tglMasuk->diff($tglKeluar)->days;

            $totalHargaKamar = 0;
            foreach ($kamarIds as $noKamar) {
                $kamar = Kamar::find($noKamar);
                if ($kamar) {
                    $totalHargaKamar += $kamar->harga * $lamaNginap;
                }
            }

            // Hitung total layanan tambahan
            $totalHargaLayanan = 0;
            $layananData = [];
            if ($request->has('layanan')) {
                foreach ($request->layanan as $layananItem) {
                    if (!empty($layananItem['id']) && !empty($layananItem['jumlah']) && $layananItem['jumlah'] > 0) {
                        $layanan = LayananTambahan::find($layananItem['id']);
                        if ($layanan) {
                            $subtotal = $layanan->harga * $layananItem['jumlah'];
                            $totalHargaLayanan += $subtotal;
                            $layananData[] = [
                                'layanan_id' => $layanan->id,
                                'jumlah' => $layananItem['jumlah'],
                                'subtotal' => $subtotal,
                            ];
                        }
                    }
                }
            }

            $totalHarga = $totalHargaKamar + $totalHargaLayanan;

            // Generate No Transaksi
            $lastTransaksi = Transaksi::orderBy('no_transaksi', 'desc')->first();
            $newNoTransaksi = $lastTransaksi ? $lastTransaksi->no_transaksi + 1 : 1;

            // Simpan transaksi
            $transaksi = Transaksi::create([
                'no_transaksi' => $newNoTransaksi,
                'id_pengunjung' => $pengunjung->id_pengunjung,
                'id_karyawan' => null,
                'jmlh_kamar' => count($kamarIds),
                'tgl_masuk' => $request->tgl_masuk,
                'tgl_keluar' => $request->tgl_keluar,
                'lama_nginap' => $lamaNginap,
                'total_harga' => $totalHarga,
                'status' => 'pending',
            ]);

            // Simpan detail transaksi
            foreach ($kamarIds as $noKamar) {
                DetailTransaksi::create([
                    'no_transaksi' => $transaksi->no_transaksi,
                    'no_kamar' => $noKamar,
                ]);
            }

            // Simpan layanan tambahan
            foreach ($layananData as $data) {
                TransaksiLayanan::create([
                    'no_transaksi' => $transaksi->no_transaksi,
                    'layanan_id' => $data['layanan_id'],
                    'jumlah' => $data['jumlah'],
                    'subtotal' => $data['subtotal'],
                ]);
            }

            DB::commit();

            return redirect('/reservasi?success=1&no_ktp=' . $pengunjung->no_ktp)
                ->with('success', 'Reservasi berhasil! Gunakan No. KTP Anda untuk cek status pesanan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function cekStatus(Request $request)
    {
        $noKtp = $request->no_ktp;
        $transaksiList = collect();
        $transaksi = null;

        if ($noKtp) {
            // Cari SEMUA pengunjung berdasarkan No KTP (karena bisa ada multiple booking)
            $pengunjungIds = Pengunjung::where('no_ktp', $noKtp)->pluck('id_pengunjung');

            if ($pengunjungIds->count() > 0) {
                // Ambil semua transaksi dari semua pengunjung dengan KTP tersebut
                $transaksiList = Transaksi::with(['pengunjung', 'detailTransaksi.kamar', 'transaksiLayanan.layananTambahan'])
                    ->whereIn('id_pengunjung', $pengunjungIds)
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Jika ada transaksi yang dipilih
                if ($request->no_transaksi) {
                    $transaksi = $transaksiList->where('no_transaksi', $request->no_transaksi)->first();
                } elseif ($transaksiList->count() > 0) {
                    $transaksi = $transaksiList->first();
                }
            }
        }

        return view('pengunjung.cek-status', compact('transaksi', 'transaksiList', 'noKtp'));
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|exists:transaksi,no_transaksi',
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $transaksi = Transaksi::find($request->no_transaksi);

        if ($transaksi->status !== 'dikonfirmasi') {
            return back()->with('error', 'Pesanan harus dikonfirmasi terlebih dahulu sebelum upload bukti bayar.');
        }

        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = 'bukti_' . $request->no_transaksi . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Pastikan folder bukti_bayar ada
            if (!Storage::disk('public')->exists('bukti_bayar')) {
                Storage::disk('public')->makeDirectory('bukti_bayar');
            }

            // Simpan file ke storage/app/public/bukti_bayar
            $path = $file->storeAs('bukti_bayar', $filename, 'public');

            $transaksi->bukti_bayar = $path;
            $transaksi->save();
        }

        $noKtp = $transaksi->pengunjung->no_ktp ?? '';
        return redirect('/reservasi/cek?no_ktp=' . $noKtp . '&no_transaksi=' . $request->no_transaksi)
            ->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi dari admin.');
    }

    public function invoice($no_transaksi)
    {
        $transaksi = Transaksi::with(['pengunjung', 'karyawan', 'detailTransaksi.kamar', 'transaksiLayanan.layanan'])
            ->where('no_transaksi', $no_transaksi)
            ->firstOrFail();

        // Hanya bisa akses invoice jika status sudah dibayar atau selesai
        if (!in_array($transaksi->status, ['dibayar', 'selesai'])) {
            $noKtp = $transaksi->pengunjung->no_ktp ?? '';
            return redirect('/reservasi/cek?no_ktp=' . $noKtp)
                ->with('error', 'Invoice hanya tersedia setelah pembayaran dikonfirmasi.');
        }

        return view('pengunjung.invoice', compact('transaksi'));
    }
}

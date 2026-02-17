@extends('layouts.app')

@section('title', 'Cek Status Pesanan')

@section('content')
<div class="section-sep">
    <h2>CEK STATUS PESANAN</h2>
    <div class="accent"></div>
    <p>Masukkan nomor KTP untuk melihat status pesanan Anda.</p>
</div>

<section class="container" style="padding:40px 0 80px">
    <div style="max-width:960px; margin:0 auto">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Form Cek Status -->
        <div class="info-card" style="margin-bottom:30px; height:auto">
            <form action="{{ route('reservasi.cek') }}" method="GET" class="form-row" style="align-items:flex-end">
                <div class="form-group" style="flex:1; margin:0">
                    <label><x-lucide-credit-card class="lucide-icon-inline" /> No. KTP</label>
                    <input type="text" name="no_ktp" class="form-control" value="{{ $noKtp ?? '' }}" placeholder="Masukkan nomor KTP Anda" required>
                </div>
                <button class="btn-reserve" type="submit" style="height:48px">Cek Status</button>
            </form>
        </div>

        @if($noKtp && (!isset($transaksiList) || $transaksiList->count() == 0))
        <div class="alert alert-error" style="text-align:center">
            <h4 style="margin:0 0 10px"><x-lucide-search-x class="lucide-icon-inline" /> Pesanan Tidak Ditemukan</h4>
            <p style="margin:0">Tidak ada pesanan dengan No. KTP <strong>{{ $noKtp }}</strong>. Pastikan nomor KTP sudah benar.</p>
        </div>
        @endif

        @if(isset($transaksiList) && $transaksiList->count() > 0)
        <!-- List Transaksi jika ada beberapa -->
        @if($transaksiList->count() > 1)
        <div class="info-card" style="margin-bottom:24px; height:auto">
            <h4><x-lucide-list class="lucide-icon-inline" /> Daftar Pesanan Anda</h4>
            <p style="color:#666; font-size:14px; margin-bottom:16px">Anda memiliki {{ $transaksiList->count() }} pesanan. Klik untuk melihat detail.</p>
            <div class="transaksi-list">
                @foreach($transaksiList as $trx)
                <a href="{{ route('reservasi.cek') }}?no_ktp={{ $noKtp }}&no_transaksi={{ $trx->no_transaksi }}"
                   class="transaksi-item {{ $transaksi && $transaksi->no_transaksi == $trx->no_transaksi ? 'active' : '' }}">
                    <div class="transaksi-item-info">
                        <strong>#{{ $trx->no_transaksi }}</strong>
                        <small>{{ date('d M Y', strtotime($trx->tgl_masuk)) }} - {{ date('d M Y', strtotime($trx->tgl_keluar)) }}</small>
                    </div>
                    <span class="status-badge-sm status-{{ $trx->status }}">
                        {{ ucfirst($trx->status) }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($transaksi)
        <!-- Status Badge -->
        <div class="status-box">
            <span class="status-badge status-{{ $transaksi->status }}">
                @php
                    $statusLabels = [
                        'pending' => 'Menunggu Konfirmasi',
                        'dikonfirmasi' => 'Dikonfirmasi - Silakan Bayar',
                        'dibayar' => 'Pembayaran Dikonfirmasi',
                        'selesai' => 'Selesai',
                        'batal' => 'Dibatalkan',
                    ];
                @endphp
                {{ $statusLabels[$transaksi->status] ?? $transaksi->status }}
            </span>
        </div>

        <div class="form-grid">
            <!-- Detail Transaksi -->
            <div class="info-card" style="height:auto">
                <h4><x-lucide-receipt class="lucide-icon-inline" /> Detail Transaksi</h4>
                <table class="info-table" style="margin-top:16px">
                    <tr>
                        <td>No. Transaksi</td>
                        <td><strong>#{{ $transaksi->no_transaksi }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal Check-in</td>
                        <td>{{ date('d M Y', strtotime($transaksi->tgl_masuk)) }} <strong style="color:var(--accent)">14:00 WIB</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal Check-out</td>
                        <td>{{ date('d M Y', strtotime($transaksi->tgl_keluar)) }} <strong style="color:var(--accent)">12:00 WIB</strong></td>
                    </tr>
                    <tr>
                        <td>Lama Menginap</td>
                        <td>{{ $transaksi->lama_nginap }} malam</td>
                    </tr>
                    <tr>
                        <td>Jumlah Kamar</td>
                        <td>{{ $transaksi->jmlh_kamar }} kamar</td>
                    </tr>
                </table>
                <div style="margin-top:16px; padding-top:16px; border-top:2px solid var(--dark); display:flex; justify-content:space-between; align-items:center">
                    <span style="font-weight:600">Total Harga</span>
                    <span style="font-size:20px; font-weight:700; color:var(--accent)">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Data Pengunjung -->
            <div class="info-card" style="height:auto">
                <h4><x-lucide-user class="lucide-icon-inline" /> Data Pemesan</h4>
                <table class="info-table" style="margin-top:16px">
                    <tr>
                        <td>Nama</td>
                        <td>{{ $transaksi->pengunjung->nm_pengunjung }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>{{ $transaksi->pengunjung->alamat }}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td>{{ $transaksi->pengunjung->no_tlp }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Kamar yang Dipesan -->
        <div class="info-card" style="margin-top:24px; height:auto">
            <h4><x-lucide-bed-double class="lucide-icon-inline" /> Kamar yang Dipesan</h4>
            <table style="width:100%; margin-top:16px; border-collapse:collapse">
                <thead>
                    <tr style="background:var(--bg)">
                        <th style="padding:14px 16px; text-align:left; font-weight:600">No. Kamar</th>
                        <th style="padding:14px 16px; text-align:left; font-weight:600">Jenis</th>
                        <th style="padding:14px 16px; text-align:right; font-weight:600">Harga/Malam</th>
                        <th style="padding:14px 16px; text-align:right; font-weight:600">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotalKamar = 0; @endphp
                    @foreach($transaksi->detailTransaksi as $detail)
                    @php $subtotalKamar += ($detail->kamar->harga ?? 0) * $transaksi->lama_nginap; @endphp
                    <tr style="border-bottom:1px solid #eee">
                        <td style="padding:14px 16px">{{ $detail->no_kamar }}</td>
                        <td style="padding:14px 16px">{{ $detail->kamar->jenis_kamar ?? '-' }}</td>
                        <td style="padding:14px 16px; text-align:right">Rp {{ number_format($detail->kamar->harga ?? 0, 0, ',', '.') }}</td>
                        <td style="padding:14px 16px; text-align:right; color:var(--accent); font-weight:500">Rp {{ number_format(($detail->kamar->harga ?? 0) * $transaksi->lama_nginap, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8f9fa">
                        <td colspan="3" style="padding:14px 16px; text-align:right; font-weight:600">Subtotal Kamar:</td>
                        <td style="padding:14px 16px; text-align:right; font-weight:600">Rp {{ number_format($subtotalKamar, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Layanan Tambahan -->
        @if($transaksi->transaksiLayanan && $transaksi->transaksiLayanan->count() > 0)
        <div class="info-card" style="margin-top:24px; height:auto">
            <h4><x-lucide-concierge-bell class="lucide-icon-inline" /> Layanan Tambahan</h4>
            <table style="width:100%; margin-top:16px; border-collapse:collapse">
                <thead>
                    <tr style="background:var(--bg)">
                        <th style="padding:14px 16px; text-align:left; font-weight:600">Layanan</th>
                        <th style="padding:14px 16px; text-align:center; font-weight:600">Jumlah</th>
                        <th style="padding:14px 16px; text-align:right; font-weight:600">Harga</th>
                        <th style="padding:14px 16px; text-align:right; font-weight:600">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotalLayanan = 0; @endphp
                    @foreach($transaksi->transaksiLayanan as $tl)
                    @php $subtotalLayanan += $tl->subtotal; @endphp
                    <tr style="border-bottom:1px solid #eee">
                        <td style="padding:14px 16px">{{ $tl->layanan->nama_layanan ?? '-' }}</td>
                        <td style="padding:14px 16px; text-align:center">{{ $tl->jumlah }}x</td>
                        <td style="padding:14px 16px; text-align:right">Rp {{ number_format($tl->layanan->harga ?? 0, 0, ',', '.') }}</td>
                        <td style="padding:14px 16px; text-align:right; color:var(--accent); font-weight:500">Rp {{ number_format($tl->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#f8f9fa">
                        <td colspan="3" style="padding:14px 16px; text-align:right; font-weight:600">Subtotal Layanan:</td>
                        <td style="padding:14px 16px; text-align:right; font-weight:600">Rp {{ number_format($subtotalLayanan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Upload Bukti Bayar (jika status dikonfirmasi) -->
        @if($transaksi->status == 'dikonfirmasi')
        <div class="info-card" style="margin-top:24px; height:auto; background:#e7f3ff; border:2px solid #17a2b8">
            <h4 style="color:#17a2b8"><x-lucide-upload class="lucide-icon-inline" /> Upload Bukti Pembayaran</h4>
            <p style="color:#666; margin-top:8px">Pesanan Anda telah dikonfirmasi. Silakan lakukan pembayaran dan upload bukti transfer.</p>

            <div style="background:var(--white); padding:20px; border-radius:8px; margin-top:16px; text-align:center">
                <p style="margin:0 0 4px; color:#666; font-size:13px">Transfer ke Rekening</p>
                <p style="margin:0; font-size:22px; font-weight:700">Bank BCA: 1234567890</p>
                <p style="margin:4px 0 0; color:#666; font-size:14px">a.n. PT Hotel X Indonesia</p>
            </div>

            <form action="{{ route('reservasi.upload') }}" method="POST" enctype="multipart/form-data" style="margin-top:20px">
                @csrf
                <input type="hidden" name="no_transaksi" value="{{ $transaksi->no_transaksi }}">
                <div class="form-group">
                    <label>Pilih File Bukti Transfer (JPG/PNG, max 2MB)</label>
                    <input type="file" name="bukti_bayar" class="form-control" accept="image/*" required style="background:var(--white)">
                </div>
                <button class="btn-reserve" type="submit" style="width:100%; text-align:center">Upload Bukti Bayar</button>
            </form>
        </div>
        @endif

        <!-- Bukti Bayar yang sudah diupload -->
        @if($transaksi->bukti_bayar && in_array($transaksi->status, ['dibayar', 'selesai']))
        <div class="info-card" style="margin-top:24px; height:auto; background:#d4edda; border:2px solid #28a745">
            <h4 style="color:#28a745"><x-lucide-check-circle class="lucide-icon-inline" /> Bukti Pembayaran Terverifikasi</h4>
            <p style="color:#666; margin-top:8px">Pembayaran Anda telah dikonfirmasi oleh admin.</p>
            <a href="{{ asset('storage/' . $transaksi->bukti_bayar) }}" target="_blank">
                <img src="{{ asset('storage/' . $transaksi->bukti_bayar) }}" style="max-width:300px; margin-top:12px; border-radius:8px; border:2px solid #28a745">
            </a>
        </div>

        <!-- Tombol Invoice -->
        <div class="info-card" style="margin-top:24px; height:auto; background:#fff3cd; border:2px solid #ffc107">
            <h4 style="color:#856404"><x-lucide-file-text class="lucide-icon-inline" /> Invoice Pembayaran</h4>
            <p style="color:#666; margin-top:8px">Invoice Anda tersedia. Anda dapat melihat, mencetak, atau mengunduh invoice.</p>
            <div style="margin-top:16px; display:flex; gap:12px; flex-wrap:wrap">
                <a href="{{ route('reservasi.invoice', $transaksi->no_transaksi) }}" class="btn-reserve" target="_blank" style="text-decoration:none">
                    <x-lucide-printer class="lucide-icon-inline" /> Lihat & Cetak Invoice
                </a>
            </div>
        </div>
        @endif

        @endif
        @endif

        <div style="text-align:center; margin-top:40px">
            <a href="/reservasi" class="btn-reserve" style="background:#666; border-color:#666; text-decoration:none">
                <x-lucide-arrow-left class="lucide-icon-inline" /> Kembali ke Reservasi
            </a>
        </div>
    </div>
</section>
@endsection

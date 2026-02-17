@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px">
    <div class="card">
        <h3 class="card-title">Transaksi #{{ $transaksi->no_transaksi }}</h3>
        <table style="margin-top:16px">
            <tr>
                <td>Status</td>
                <td><span class="badge badge-{{ $transaksi->status }}">{{ $transaksi->status }}</span></td>
            </tr>
            <tr>
                <td>Check-in</td>
                <td>{{ date('d M Y', strtotime($transaksi->tgl_masuk)) }} <strong style="color:#b78f5a">14:00 WIB</strong></td>
            </tr>
            <tr>
                <td>Check-out</td>
                <td>{{ date('d M Y', strtotime($transaksi->tgl_keluar)) }} <strong style="color:#b78f5a">12:00 WIB</strong></td>
            </tr>
            <tr>
                <td>Lama Menginap</td>
                <td>{{ $transaksi->lama_nginap }} malam</td>
            </tr>
            <tr>
                <td>Jumlah Kamar</td>
                <td>{{ $transaksi->jmlh_kamar }} kamar</td>
            </tr>
            <tr>
                <td>Total Harga</td>
                <td><strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Dikonfirmasi Oleh</td>
                <td>{{ $transaksi->karyawan->nm_karyawan ?? '-' }}</td>
            </tr>
        </table>

        @if($transaksi->bukti_bayar)
        <div style="margin-top:20px">
            <strong>Bukti Pembayaran:</strong><br>
            <a href="{{ asset('storage/' . $transaksi->bukti_bayar) }}" target="_blank">
                <img src="{{ asset('storage/' . $transaksi->bukti_bayar) }}" style="max-width:300px; margin-top:8px; border-radius:6px">
            </a>
        </div>
        @endif
    </div>

    <div class="card">
        <h3 class="card-title">Data Pengunjung</h3>
        @if($transaksi->pengunjung)
        <table style="margin-top:16px">
            <tr>
                <td style="width:120px; color:#666">ID</td>
                <td>{{ $transaksi->pengunjung->id_pengunjung }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>{{ $transaksi->pengunjung->nm_pengunjung }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>{{ $transaksi->pengunjung->alamat }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>{{ $transaksi->pengunjung->jk }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>{{ $transaksi->pengunjung->no_tlp }}</td>
            </tr>
            <tr>
                <td>No. KTP</td>
                <td>{{ $transaksi->pengunjung->no_ktp }}</td>
            </tr>
        </table>
        @endif
    </div>
</div>

<div class="card" style="margin-top:24px">
    <h3 class="card-title">Kamar yang Dipesan</h3>
    <table style="margin-top:16px">
        <thead>
            <tr>
                <th>No. Kamar</th>
                <th>Jenis</th>
                <th>Harga/Malam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi->detailTransaksi as $detail)
            <tr>
                <td>{{ $detail->no_kamar }}</td>
                <td>{{ $detail->kamar->jenis_kamar ?? '-' }}</td>
                <td>Rp {{ number_format($detail->kamar->harga ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align:center; color:#999">Tidak ada data kamar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transaksi->transaksiLayanan && $transaksi->transaksiLayanan->count() > 0)
<div class="card" style="margin-top:24px">
    <h3 class="card-title">Layanan Tambahan</h3>
    <table style="margin-top:16px">
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Harga</th>
                <th style="text-align:center">Jumlah</th>
                <th style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLayanan = 0; @endphp
            @foreach($transaksi->transaksiLayanan as $layanan)
            @php $totalLayanan += $layanan->subtotal; @endphp
            <tr>
                <td>{{ $layanan->layananTambahan->nama_layanan }}</td>
                <td>Rp {{ number_format($layanan->layananTambahan->harga, 0, ',', '.') }}</td>
                <td style="text-align:center">{{ $layanan->jumlah }}</td>
                <td style="text-align:right">Rp {{ number_format($layanan->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right"><strong>Total Layanan:</strong></td>
                <td style="text-align:right"><strong>Rp {{ number_format($totalLayanan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

<div style="margin-top:24px; display:flex; gap:12px; flex-wrap:wrap">
    <a href="/admin/transaksi" class="btn btn-secondary"><x-lucide-arrow-left class="lucide-icon-btn" /> Kembali</a>
    @if(in_array($transaksi->status, ['dibayar', 'selesai']))
    <a href="{{ route('reservasi.invoice', $transaksi->no_transaksi) }}" target="_blank" class="btn btn-primary"><x-lucide-printer class="lucide-icon-btn" /> Cetak Invoice</a>
    @endif
</div>
@endsection

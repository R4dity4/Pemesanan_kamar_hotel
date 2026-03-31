@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="admin-detail-grid">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3 class="card-title">Transaksi #{{ $transaksi->no_transaksi }}</h3>
            @if(in_array($transaksi->status, ['pending', 'dikonfirmasi']))
            <button type="button" class="btn btn-secondary" onclick="toggleEdit()" style="padding:4px 12px; font-size:14px;">
                Edit
            </button>
            @endif
        </div>

        <!-- View Mode -->
        <table id="viewMode" style="margin-top:16px; width:100%">
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

        <!-- Edit Mode -->
        <form id="editMode" method="POST" action="{{ route('admin.transaksi.update-detail', $transaksi->no_transaksi) }}" style="display:none; margin-top:16px;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 12px;">
                <label style="display:block; margin-bottom:4px; font-weight:600; font-size:14px;">Tanggal Check-in</label>
                <input type="date" name="tgl_masuk" value="{{ $transaksi->tgl_masuk }}" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display:block; margin-bottom:4px; font-weight:600; font-size:14px;">Tanggal Check-out</label>
                <input type="date" name="tgl_keluar" value="{{ $transaksi->tgl_keluar }}" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:6px;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display:block; margin-bottom:4px; font-weight:600; font-size:14px;">Pilih Kamar yang Dipesan</label>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap:10px; max-height: 200px; overflow-y: auto; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @php
                        $kamarTerpilih = $transaksi->detailTransaksi->pluck('no_kamar')->toArray();
                    @endphp
                    @foreach($semuaKamar as $kamar)
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer;" title="{{ $kamar->jenis_kamar }} - Rp{{ number_format($kamar->harga,0,',','.') }}">
                            <input type="checkbox" name="kamar_ids[]" value="{{ $kamar->no_kamar }}"
                                {{ in_array($kamar->no_kamar, $kamarTerpilih) ? 'checked' : '' }}>
                            <span>{{ $kamar->no_kamar }}</span>
                        </label>
                    @endforeach
                </div>
                <small style="color:#666; font-size:12px;">Cawang opsi kamar yang akan dipesan.</small>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; font-size:14px;">Layanan Tambahan</label>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach($semuaLayanan as $layanan)
                        @php
                            $selectedLayanan = $transaksi->transaksiLayanan->where('layanan_id', $layanan->id)->first();
                            $jumlahLayanan = $selectedLayanan ? $selectedLayanan->jumlah : 0;
                        @endphp
                        <div style="display:flex; align-items:center; justify-content:space-between; background:#f9f9f9; padding:8px 12px; border-radius:6px; border:1px solid #eee;">
                            <div>
                                <strong style="display:block; font-size:14px;">{{ $layanan->nama_layanan }}</strong>
                                <small style="color:#666;">Rp {{ number_format($layanan->harga, 0, ',', '.') }} / item</small>
                            </div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <label style="font-size:12px; color:#555;">Jumlah:</label>
                                <input type="number" name="layanan[{{ $layanan->id }}]" value="{{ $jumlahLayanan }}" min="0" 
                                       style="width:70px; padding:6px; border:1px solid #ddd; border-radius:4px;">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="btn btn-primary" style="padding:6px 12px;">Simpan</button>
                <button type="button" class="btn btn-secondary" style="padding:6px 12px;" onclick="toggleEdit()">Batal</button>
            </div>
        </form>

        @if($transaksi->bukti_bayar)
        <div style="margin-top:20px; padding:12px; border:1px solid #ddd; border-radius:8px; background:#fefefe;">
            <strong>Bukti Pembayaran:</strong><br>
            <a href="{{ asset('storage/' . $transaksi->bukti_bayar) }}" target="_blank">
                <img src="{{ asset('storage/' . $transaksi->bukti_bayar) }}" style="max-width:300px; margin-top:8px; border-radius:6px; display:block">
            </a>
            @if($transaksi->status == 'dikonfirmasi')
            <div style="margin-top:12px; display:flex; gap:8px;">
                <form action="{{ route('admin.transaksi.bayar', $transaksi->no_transaksi) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary" onclick="return confirm('Verifikasi pembayaran ini?')" style="background:#28a745; border-color:#28a745;">Terima & Verifikasi</button>
                </form>
                <form action="{{ route('admin.transaksi.tolakBukti', $transaksi->no_transaksi) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary" onclick="return confirm('Tolak bukti pembayaran ini? Foto bukti akan dihapus dan pengunjung harus mengupload ulang.')" style="background:#dc3545; border-color:#dc3545;">Tolak Bukti (Tidak Valid)</button>
                </form>
            </div>
            @endif
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
    <a href="/admin/transaksi" class="btn btn-secondary"><svg class="lucide-icon-btn"><use href="#i-arrow-left"/></svg> Kembali</a>
    @if(in_array($transaksi->status, ['dibayar', 'selesai']))
    <a href="{{ route('reservasi.invoice', $transaksi->no_transaksi) }}" target="_blank" class="btn btn-primary"><svg class="lucide-icon-btn"><use href="#i-printer"/></svg> Cetak Invoice</a>
    @endif
</div>

<script>
function toggleEdit() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    if (viewMode.style.display === 'none') {
        viewMode.style.display = 'table';
        editMode.style.display = 'none';
    } else {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    }
}
</script>
@endsection

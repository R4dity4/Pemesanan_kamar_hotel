@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="card filter-card">
    <form action="/admin/laporan" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="filter-group">
                <label>Periode</label>
                <select name="periode" id="periode" class="form-control" onchange="togglePeriodInputs()">
                    <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ $periode == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            <div class="filter-group" id="input-tanggal" style="{{ $periode != 'harian' ? 'display:none' : '' }}">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" max="{{ date('Y-m-d') }}">
            </div>
            <div class="filter-group" id="input-bulan" style="{{ $periode != 'bulanan' ? 'display:none' : '' }}">
                <label>Bulan</label>
                <select name="bulan" class="form-control">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="filter-group" id="input-tahun" style="{{ $periode == 'harian' ? 'display:none' : '' }}">
                <label>Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach($years as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary"><x-lucide-bar-chart-3 class="lucide-icon-btn" /> Tampilkan</button>
            </div>
        </div>
    </form>
</div>

<!-- Stats Overview -->
<div class="stats-grid-4">
    <div class="stat-mini">
        <span class="stat-icon"><x-lucide-clipboard-list class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $data['totalTransaksi'] }}</div>
            <div class="stat-label">Total Transaksi</div>
        </div>
    </div>
    <div class="stat-mini success">
        <span class="stat-icon"><x-lucide-wallet class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ number_format($data['totalRevenue'] / 1000000, 1) }}jt</div>
            <div class="stat-label">Pendapatan</div>
        </div>
    </div>
    <div class="stat-mini info">
        <span class="stat-icon"><x-lucide-bed-double class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $data['totalKamarDipesan'] }}</div>
            <div class="stat-label">Kamar Dipesan</div>
        </div>
    </div>
    <div class="stat-mini warning">
        <span class="stat-icon"><x-lucide-calendar-days class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $data['avgLamaNginap'] }}</div>
            <div class="stat-label">Rata-rata Malam</div>
        </div>
    </div>
</div>

<!-- Status Distribution -->
<div class="dashboard-row">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-bar-chart-3 class="lucide-icon-inline" /> Distribusi Status</h3>
        </div>
        <div class="status-grid">
            <div class="status-item pending">
                <div class="status-count">{{ $data['statusStats']['pending'] }}</div>
                <div class="status-label">Pending</div>
            </div>
            <div class="status-item dikonfirmasi">
                <div class="status-count">{{ $data['statusStats']['dikonfirmasi'] }}</div>
                <div class="status-label">Dikonfirmasi</div>
            </div>
            <div class="status-item dibayar">
                <div class="status-count">{{ $data['statusStats']['dibayar'] }}</div>
                <div class="status-label">Dibayar</div>
            </div>
            <div class="status-item selesai">
                <div class="status-count">{{ $data['statusStats']['selesai'] }}</div>
                <div class="status-label">Selesai</div>
            </div>
            <div class="status-item batal">
                <div class="status-count">{{ $data['statusStats']['batal'] }}</div>
                <div class="status-label">Batal</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-trophy class="lucide-icon-inline" /> Kamar Terpopuler</h3>
        </div>
        @if(count($data['topRooms']) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kamar</th>
                        <th>Jenis</th>
                        <th>Dipesan</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['topRooms'] as $room)
                    <tr>
                        <td><strong>#{{ $room['no_kamar'] }}</strong></td>
                        <td>{{ $room['jenis'] }}</td>
                        <td>{{ $room['count'] }}x</td>
                        <td>Rp {{ number_format($room['revenue'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">Tidak ada data</div>
        @endif
    </div>
</div>

@if($periode === 'tahunan' && count($data['monthlyData']) > 0)
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><x-lucide-calendar class="lucide-icon-inline" /> Rekap Bulanan {{ $tahun }}</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Jumlah Transaksi</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['monthlyData'] as $md)
                <tr>
                    <td><strong>{{ $md['bulan'] }}</strong></td>
                    <td>{{ $md['transaksi'] }}</td>
                    <td>Rp {{ number_format($md['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight:bold; background:#f8f9fa">
                    <td>TOTAL</td>
                    <td>{{ array_sum(array_column($data['monthlyData'], 'transaksi')) }}</td>
                    <td>Rp {{ number_format(array_sum(array_column($data['monthlyData'], 'revenue')), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

@if($periode === 'bulanan' && count($data['dailyData']) > 0)
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><x-lucide-calendar-days class="lucide-icon-inline" /> Rekap Harian {{ \Carbon\Carbon::create($tahun, $bulan, 1)->format('F Y') }}</h3>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Transaksi</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['dailyData'] as $dd)
                <tr>
                    <td><strong>{{ $dd['tanggal'] }}</strong></td>
                    <td>{{ $dd['transaksi'] }}</td>
                    <td>Rp {{ number_format($dd['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Transaction List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><x-lucide-clipboard-list class="lucide-icon-inline" /> Daftar Transaksi - {{ $data['periodLabel'] }}</h3>
        <a href="/admin/laporan/download?periode={{ $periode }}&tahun={{ $tahun }}&bulan={{ $bulan }}&tanggal={{ $tanggal }}" class="btn btn-sm btn-success">
            <x-lucide-download class="lucide-icon-btn" /> Download PDF
        </a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengunjung</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Kamar</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['transaksis'] as $t)
                <tr>
                    <td>#{{ $t->no_transaksi }}</td>
                    <td>{{ $t->pengunjung->nm_pengunjung ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_masuk)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_keluar)->format('d M Y') }}</td>
                    <td>{{ $t->jmlh_kamar }}</td>
                    <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ $t->status }}">{{ $t->status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#999">Tidak ada transaksi untuk periode ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function togglePeriodInputs() {
    const periode = document.getElementById('periode').value;
    document.getElementById('input-tanggal').style.display = periode === 'harian' ? 'flex' : 'none';
    document.getElementById('input-bulan').style.display = periode === 'bulanan' ? 'flex' : 'none';
    document.getElementById('input-tahun').style.display = periode !== 'harian' ? 'flex' : 'none';
}
</script>

<style>
.stats-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

@media (max-width: 900px) {
    .stats-grid-4 { grid-template-columns: repeat(2, 1fr); }
}

.stat-mini {
    background: #fff;
    border-radius: 8px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.stat-mini .stat-icon { font-size: 24px; }
.stat-mini .stat-number { font-size: 22px; font-weight: 700; color: #1a1a1a; }
.stat-mini .stat-label { font-size: 12px; color: #666; }
.stat-mini.success { border-left: 4px solid #28a745; }
.stat-mini.info { border-left: 4px solid #17a2b8; }
.stat-mini.warning { border-left: 4px solid #ffc107; }

.dashboard-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 900px) {
    .dashboard-row { grid-template-columns: 1fr; }
}

.status-grid {
    display: flex;
    gap: 12px;
    padding: 16px;
    flex-wrap: wrap;
}

.status-item {
    flex: 1;
    min-width: 80px;
    text-align: center;
    padding: 16px 12px;
    border-radius: 8px;
    background: #f8f9fa;
}

.status-item .status-count {
    font-size: 24px;
    font-weight: 700;
}

.status-item .status-label {
    font-size: 11px;
    color: #666;
    margin-top: 4px;
}

.status-item.pending { border-bottom: 3px solid #ffc107; }
.status-item.pending .status-count { color: #ffc107; }

.status-item.dikonfirmasi { border-bottom: 3px solid #17a2b8; }
.status-item.dikonfirmasi .status-count { color: #17a2b8; }

.status-item.dibayar { border-bottom: 3px solid #28a745; }
.status-item.dibayar .status-count { color: #28a745; }

.status-item.selesai { border-bottom: 3px solid #6c757d; }
.status-item.selesai .status-count { color: #6c757d; }

.status-item.batal { border-bottom: 3px solid #dc3545; }
.status-item.batal .status-count { color: #dc3545; }

.empty-state {
    padding: 40px;
    text-align: center;
    color: #999;
}

.filter-card { margin-bottom: 20px; }
.filter-form { padding: 16px; }
.filter-row { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
.filter-group { display: flex; flex-direction: column; gap: 4px; }
.filter-group label { font-size: 12px; color: #666; font-weight: 500; }
.filter-group .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-width: 150px; }
.filter-actions { display: flex; gap: 8px; }
</style>
@endsection

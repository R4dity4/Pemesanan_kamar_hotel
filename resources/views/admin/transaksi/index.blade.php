@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid-4">
    <div class="stat-mini">
        <span class="stat-icon"><x-lucide-clipboard-list class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
            <div class="stat-label">Total</div>
        </div>
    </div>
    <div class="stat-mini warning">
        <span class="stat-icon"><x-lucide-clock class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-mini success">
        <span class="stat-icon"><x-lucide-check class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $stats['selesai'] ?? 0 }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="stat-mini info">
        <span class="stat-icon"><x-lucide-wallet class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ number_format(($stats['revenue'] ?? 0) / 1000000, 1) }}jt</div>
            <div class="stat-label">Pendapatan</div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card filter-card">
    <form action="/admin/transaksi" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="filter-group">
                <label>Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No transaksi / nama tamu" value="{{ request('search') }}">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary"><x-lucide-search class="lucide-icon-btn" /> Filter</button>
                <a href="/admin/transaksi" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Transaksi</h3>
        <a href="/admin/transaksi/export?{{ http_build_query(request()->all()) }}" class="btn btn-sm btn-success"><x-lucide-download class="lucide-icon-btn" /> Export CSV</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengunjung</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td>#{{ $t->no_transaksi }}</td>
                    <td>
                        <div class="guest-cell">
                            <strong>{{ $t->pengunjung->nm_pengunjung ?? '-' }}</strong>
                            @if($t->pengunjung && $t->pengunjung->email)
                            <small>{{ $t->pengunjung->email }}</small>
                            @endif
                        </div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_masuk)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_keluar)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ $t->status }}">{{ $t->status }}</span></td>
                    <td>
                        <div class="btn-group">
                            <a href="/admin/transaksi/{{ $t->no_transaksi }}" class="btn btn-sm btn-secondary">Detail</a>
                            @if($t->status == 'pending')
                                <form action="/admin/transaksi/{{ $t->no_transaksi }}/konfirmasi" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Konfirmasi</button>
                                </form>
                            @endif
                            @if($t->status == 'dikonfirmasi' && $t->bukti_bayar)
                                <form action="/admin/transaksi/{{ $t->no_transaksi }}/bayar" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Konfirmasi Bayar</button>
                                </form>
                            @endif
                            @if($t->status == 'dibayar')
                                <form action="/admin/transaksi/{{ $t->no_transaksi }}/selesai" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                                </form>
                            @endif
                            @if(!in_array($t->status, ['selesai', 'batal']))
                                <form action="/admin/transaksi/{{ $t->no_transaksi }}/batal" method="POST" onsubmit="return confirm('Batalkan transaksi ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Batal</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#999">Tidak ada transaksi ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($transaksis, 'links'))
    <div class="pagination-wrapper">
        {{ $transaksis->appends(request()->all())->links() }}
    </div>
    @endif
</div>

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

.stat-mini .stat-icon {
    font-size: 24px;
}

.stat-mini .stat-number {
    font-size: 22px;
    font-weight: 700;
    color: #1a1a1a;
}

.stat-mini .stat-label {
    font-size: 12px;
    color: #666;
}

.stat-mini.warning { border-left: 4px solid #ffc107; }
.stat-mini.success { border-left: 4px solid #28a745; }
.stat-mini.info { border-left: 4px solid #17a2b8; }

.filter-card {
    margin-bottom: 20px;
}

.filter-form {
    padding: 16px;
}

.filter-row {
    display: flex;
    gap: 16px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-group label {
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.filter-group .form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    min-width: 150px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.guest-cell {
    display: flex;
    flex-direction: column;
}

.guest-cell small {
    color: #999;
    font-size: 12px;
}

.pagination-wrapper {
    padding: 16px;
    display: flex;
    justify-content: center;
}
</style>
@endsection

@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')
<!-- Stats -->
<div class="stats-grid-3">
    <div class="stat-mini">
        <span class="stat-icon"><x-lucide-mail class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Pesan</div>
        </div>
    </div>
    <div class="stat-mini warning">
        <span class="stat-icon"><x-lucide-bell class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $stats['belum_dibaca'] }}</div>
            <div class="stat-label">Belum Dibaca</div>
        </div>
    </div>
    <div class="stat-mini success">
        <span class="stat-icon"><x-lucide-check class="lucide-icon" /></span>
        <div class="stat-data">
            <div class="stat-number">{{ $stats['sudah_dibaca'] }}</div>
            <div class="stat-label">Sudah Dibaca</div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card filter-card">
    <form action="/admin/pesan" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua</option>
                    <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Topik</label>
                <select name="topik" class="form-control">
                    <option value="">Semua Topik</option>
                    <option value="umum" {{ request('topik') == 'umum' ? 'selected' : '' }}>Pertanyaan Umum</option>
                    <option value="reservasi" {{ request('topik') == 'reservasi' ? 'selected' : '' }}>Reservasi</option>
                    <option value="acara" {{ request('topik') == 'acara' ? 'selected' : '' }}>Acara & Meeting</option>
                    <option value="kerjasama" {{ request('topik') == 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                    <option value="lainnya" {{ request('topik') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama / email / pesan" value="{{ request('search') }}">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary"><x-lucide-search class="lucide-icon-btn" /> Filter</button>
                <a href="/admin/pesan" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><x-lucide-mail class="lucide-icon-inline" /> Pesan Masuk</h3>
        @if($stats['belum_dibaca'] > 0)
        <form action="/admin/pesan/mark-all-read" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-secondary"><x-lucide-check-check class="lucide-icon-btn" /> Tandai Semua Dibaca</button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success" style="margin:16px; background:#d4edda; color:#155724; padding:12px 16px; border-radius:8px">
        <x-lucide-check class="lucide-icon-btn" /> {{ session('success') }}
    </div>
    @endif

    <div class="pesan-list">
        @forelse($pesans as $pesan)
        <div class="pesan-item {{ !$pesan->dibaca ? 'unread' : '' }}">
            <div class="pesan-indicator">
                @if(!$pesan->dibaca)
                <span class="dot"></span>
                @endif
            </div>
            <div class="pesan-content">
                <div class="pesan-header">
                    <div class="pesan-sender">
                        <strong>{{ $pesan->nama }}</strong>
                        <span class="pesan-email">{{ $pesan->email }}</span>
                    </div>
                    <div class="pesan-meta">
                        <span class="badge badge-{{ $pesan->topik }}">{{ ucfirst($pesan->topik) }}</span>
                        <span class="pesan-time">{{ $pesan->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="pesan-preview">
                    {{ Str::limit($pesan->pesan, 120) }}
                </div>
            </div>
            <div class="pesan-actions">
                <a href="/admin/pesan/{{ $pesan->id }}" class="btn btn-sm btn-primary">Baca</a>
                <form action="/admin/pesan/{{ $pesan->id }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <p>📭 Tidak ada pesan</p>
        </div>
        @endforelse
    </div>

    @if($pesans->hasPages())
    <div class="pagination-wrapper">
        {{ $pesans->appends(request()->all())->links() }}
    </div>
    @endif
</div>

<style>
.stats-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .stats-grid-3 { grid-template-columns: 1fr; }
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
.stat-mini.warning { border-left: 4px solid #ffc107; }
.stat-mini.success { border-left: 4px solid #28a745; }

.filter-card { margin-bottom: 20px; }
.filter-form { padding: 16px; }
.filter-row { display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
.filter-group { display: flex; flex-direction: column; gap: 4px; }
.filter-group label { font-size: 12px; color: #666; font-weight: 500; }
.filter-group .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-width: 150px; }
.filter-actions { display: flex; gap: 8px; }

.pesan-list { padding: 0; }

.pesan-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    transition: background 0.2s;
}

.pesan-item:hover { background: #f9f9f9; }
.pesan-item.unread { background: #fffef5; }
.pesan-item:last-child { border-bottom: none; }

.pesan-indicator {
    width: 10px;
    padding-top: 6px;
}

.pesan-indicator .dot {
    display: block;
    width: 8px;
    height: 8px;
    background: #ffc107;
    border-radius: 50%;
}

.pesan-content { flex: 1; min-width: 0; }

.pesan-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 6px;
}

.pesan-sender strong { display: block; font-size: 15px; }
.pesan-email { font-size: 13px; color: #666; }

.pesan-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.pesan-time { font-size: 12px; color: #999; }

.pesan-preview {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
}

.pesan-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.badge-umum { background: #6c757d; color: #fff; }
.badge-reservasi { background: #17a2b8; color: #fff; }
.badge-acara { background: #6f42c1; color: #fff; }
.badge-kerjasama { background: #28a745; color: #fff; }
.badge-lainnya { background: #ffc107; color: #333; }

.empty-state {
    padding: 60px 20px;
    text-align: center;
    color: #999;
}

.pagination-wrapper {
    padding: 16px;
    display: flex;
    justify-content: center;
}
</style>
@endsection

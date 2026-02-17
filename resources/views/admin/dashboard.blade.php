@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card accent">
        <div class="stat-icon"><x-lucide-building class="lucide-icon" /></div>
        <div class="stat-info">
            <div class="label">Total Kamar</div>
            <div class="value">{{ $totalKamar }}</div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-icon"><x-lucide-check-circle class="lucide-icon" /></div>
        <div class="stat-info">
            <div class="label">Kamar Tersedia</div>
            <div class="value">{{ $kamarTersedia }}</div>
        </div>
    </div>
    <div class="stat-card info">
        <div class="stat-icon"><x-lucide-clipboard-list class="lucide-icon" /></div>
        <div class="stat-info">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $totalTransaksi }}</div>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><x-lucide-clock class="lucide-icon" /></div>
        <div class="stat-info">
            <div class="label">Menunggu Konfirmasi</div>
            <div class="value">{{ $transaksiPending }}</div>
        </div>
    </div>
</div>

<!-- Revenue & Growth -->
<div class="dashboard-row">
    <div class="card revenue-card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-wallet class="lucide-icon-inline" /> Pendapatan Bulan Ini</h3>
        </div>
        <div class="revenue-content">
            <div class="revenue-amount">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</div>
            <div class="revenue-growth {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}% dari bulan lalu
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-bar-chart-3 class="lucide-icon-inline" /> Status Transaksi</h3>
        </div>
        <div class="status-bars">
            <div class="status-bar-item">
                <span class="status-label">Pending</span>
                <div class="status-bar">
                    <div class="status-fill pending" style="width: {{ $totalTransaksi > 0 ? ($statusStats['pending'] / $totalTransaksi * 100) : 0 }}%"></div>
                </div>
                <span class="status-count">{{ $statusStats['pending'] }}</span>
            </div>
            <div class="status-bar-item">
                <span class="status-label">Dikonfirmasi</span>
                <div class="status-bar">
                    <div class="status-fill dikonfirmasi" style="width: {{ $totalTransaksi > 0 ? ($statusStats['dikonfirmasi'] / $totalTransaksi * 100) : 0 }}%"></div>
                </div>
                <span class="status-count">{{ $statusStats['dikonfirmasi'] }}</span>
            </div>
            <div class="status-bar-item">
                <span class="status-label">Dibayar</span>
                <div class="status-bar">
                    <div class="status-fill dibayar" style="width: {{ $totalTransaksi > 0 ? ($statusStats['dibayar'] / $totalTransaksi * 100) : 0 }}%"></div>
                </div>
                <span class="status-count">{{ $statusStats['dibayar'] }}</span>
            </div>
            <div class="status-bar-item">
                <span class="status-label">Selesai</span>
                <div class="status-bar">
                    <div class="status-fill selesai" style="width: {{ $totalTransaksi > 0 ? ($statusStats['selesai'] / $totalTransaksi * 100) : 0 }}%"></div>
                </div>
                <span class="status-count">{{ $statusStats['selesai'] }}</span>
            </div>
            <div class="status-bar-item">
                <span class="status-label">Batal</span>
                <div class="status-bar">
                    <div class="status-fill batal" style="width: {{ $totalTransaksi > 0 ? ($statusStats['batal'] / $totalTransaksi * 100) : 0 }}%"></div>
                </div>
                <span class="status-count">{{ $statusStats['batal'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="dashboard-row">
    <div class="card chart-card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-trending-up class="lucide-icon-inline" /> Pendapatan 6 Bulan Terakhir</h3>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="card chart-card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-calendar class="lucide-icon-inline" /> Booking 7 Hari Terakhir</h3>
        </div>
        <div class="chart-container">
            <canvas id="bookingsChart"></canvas>
        </div>
    </div>
</div>

<!-- Today's Activity -->
<div class="dashboard-row">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-log-in class="lucide-icon-inline" /> Check-in Hari Ini</h3>
            <span class="badge badge-info">{{ count($todayCheckIns) }}</span>
        </div>
        @if(count($todayCheckIns) > 0)
        <div class="checkin-list">
            @foreach($todayCheckIns as $checkin)
            <div class="checkin-item">
                <div class="guest-info">
                    <strong>{{ $checkin->pengunjung->nm_pengunjung ?? 'Guest' }}</strong>
                    <small>#{{ $checkin->no_transaksi }}</small>
                </div>
                <span class="badge badge-{{ $checkin->status }}">{{ $checkin->status }}</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">Tidak ada check-in hari ini</div>
        @endif
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-log-out class="lucide-icon-inline" /> Check-out Hari Ini</h3>
            <span class="badge badge-warning">{{ count($todayCheckOuts) }}</span>
        </div>
        @if(count($todayCheckOuts) > 0)
        <div class="checkin-list">
            @foreach($todayCheckOuts as $checkout)
            <div class="checkin-item">
                <div class="guest-info">
                    <strong>{{ $checkout->pengunjung->nm_pengunjung ?? 'Guest' }}</strong>
                    <small>#{{ $checkout->no_transaksi }}</small>
                </div>
                <a href="/admin/transaksi/{{ $checkout->no_transaksi }}" class="btn btn-sm btn-secondary">Detail</a>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">Tidak ada check-out hari ini</div>
        @endif
    </div>
</div>

<!-- Upcoming & Recent -->
<div class="dashboard-row">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-calendar-days class="lucide-icon-inline" /> Reservasi Mendatang</h3>
            <a href="/admin/transaksi" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        @if(count($upcomingReservations) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tamu</th>
                        <th>Check-in</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingReservations as $r)
                    <tr>
                        <td>#{{ $r->no_transaksi }}</td>
                        <td>{{ $r->pengunjung->nm_pengunjung ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->tgl_masuk)->format('d M') }}</td>
                        <td><span class="badge badge-{{ $r->status }}">{{ $r->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">Tidak ada reservasi mendatang</div>
        @endif
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><x-lucide-clock class="lucide-icon-inline" /> Transaksi Terbaru</h3>
            <a href="/admin/transaksi" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengunjung</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransaksi as $t)
                    <tr>
                        <td>#{{ $t->no_transaksi }}</td>
                        <td>{{ $t->pengunjung->nm_pengunjung ?? '-' }}</td>
                        <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td><span class="badge badge-{{ $t->status }}">{{ $t->status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#999">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Room Types -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><x-lucide-bed-double class="lucide-icon-inline" /> Distribusi Tipe Kamar</h3>
    </div>
    <div class="room-types-grid">
        @foreach($roomTypes as $type)
        <div class="room-type-card">
            <div class="room-type-count">{{ $type->total }}</div>
            <div class="room-type-name">{{ $type->jenis_kamar }}</div>
        </div>
        @endforeach
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($monthlyRevenue)->pluck('month')) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode(collect($monthlyRevenue)->pluck('revenue')) !!},
                borderColor: '#b78f5a',
                backgroundColor: 'rgba(183, 143, 90, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                        }
                    }
                }
            }
        }
    });

    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($dailyBookings)->pluck('date')) !!},
            datasets: [{
                label: 'Booking',
                data: {!! json_encode(collect($dailyBookings)->pluck('count')) !!},
                backgroundColor: '#17a2b8',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>

<style>
.dashboard-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 24px;
}

@media (max-width: 900px) {
    .dashboard-row {
        grid-template-columns: 1fr;
    }
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.stat-card {
    background: #fff;
    padding: 24px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.stat-icon {
    font-size: 32px;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    border-radius: 12px;
}

.stat-card.accent .stat-icon { background: rgba(183,143,90,0.15); }
.stat-card.success .stat-icon { background: rgba(40,167,69,0.15); }
.stat-card.info .stat-icon { background: rgba(23,162,184,0.15); }
.stat-card.warning .stat-icon { background: rgba(255,193,7,0.15); }

.stat-info .label {
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.stat-info .value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
}

.revenue-card .revenue-content {
    padding: 20px;
}

.revenue-amount {
    font-size: 36px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.revenue-growth {
    font-size: 14px;
    font-weight: 600;
}

.revenue-growth.positive { color: #28a745; }
.revenue-growth.negative { color: #dc3545; }

.status-bars {
    padding: 16px;
}

.status-bar-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.status-bar-item:last-child { margin-bottom: 0; }

.status-label {
    width: 100px;
    font-size: 13px;
    color: #666;
}

.status-bar {
    flex: 1;
    height: 8px;
    background: #eee;
    border-radius: 4px;
    overflow: hidden;
}

.status-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.status-fill.pending { background: #ffc107; }
.status-fill.dikonfirmasi { background: #17a2b8; }
.status-fill.dibayar { background: #28a745; }
.status-fill.selesai { background: #6c757d; }
.status-fill.batal { background: #dc3545; }

.status-count {
    width: 30px;
    text-align: right;
    font-weight: 600;
    font-size: 14px;
}

.chart-card .chart-container {
    height: 250px;
    padding: 16px;
}

.checkin-list {
    padding: 8px 16px;
}

.checkin-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.checkin-item:last-child { border-bottom: none; }

.guest-info strong {
    display: block;
    font-size: 14px;
}

.guest-info small {
    color: #999;
    font-size: 12px;
}

.empty-state {
    padding: 40px;
    text-align: center;
    color: #999;
    font-size: 14px;
}

.room-types-grid {
    display: flex;
    gap: 16px;
    padding: 16px;
    flex-wrap: wrap;
}

.room-type-card {
    background: #f8f9fa;
    padding: 20px 32px;
    border-radius: 8px;
    text-align: center;
    min-width: 120px;
}

.room-type-count {
    font-size: 32px;
    font-weight: 700;
    color: #b78f5a;
}

.room-type-name {
    font-size: 13px;
    color: #666;
    margin-top: 4px;
}
</style>
@endsection

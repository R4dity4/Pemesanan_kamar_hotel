<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $judulPeriode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #b78f5a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .header h1 span {
            color: #b78f5a;
        }
        .header p {
            color: #666;
            font-size: 10px;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h2 {
            font-size: 16px;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .report-title p {
            color: #666;
            font-size: 10px;
        }
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #eee;
        }
        .stat-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .stat-box .label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a1a;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-dikonfirmasi { background: #d1ecf1; color: #0c5460; }
        .badge-dibayar { background: #d4edda; color: #155724; }
        .badge-selesai { background: #e2e3e5; color: #383d41; }
        .badge-batal { background: #f8d7da; color: #721c24; }
        .summary-box {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-row {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 5px;
        }
        .summary-item .count {
            font-size: 16px;
            font-weight: bold;
        }
        .summary-item .label {
            font-size: 9px;
            color: #666;
        }
        .summary-item.pending .count { color: #ffc107; }
        .summary-item.dikonfirmasi .count { color: #17a2b8; }
        .summary-item.dibayar .count { color: #28a745; }
        .summary-item.selesai .count { color: #6c757d; }
        .summary-item.batal .count { color: #dc3545; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>HOTEL<span>X</span></h1>
        <p>Jl. Bukit Watuwila VI No.26, Bringin, Ngaliyan, Semarang</p>
        <p>Telp: +62 00 2606 2007 | Email: info@hotelx.id</p>
    </div>

    <!-- Report Title -->
    <div class="report-title">
        <h2>LAPORAN TRANSAKSI</h2>
        <p>Periode: {{ $judulPeriode }}</p>
        <p>Dicetak: {{ $tanggalCetak }}</p>
    </div>

    <!-- Stats Overview -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="value">{{ $data['totalTransaksi'] }}</div>
            <div class="label">Total Transaksi</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($data['totalRevenue'], 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $data['totalKamarDipesan'] }}</div>
            <div class="label">Kamar Dipesan</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $data['avgLamaNginap'] }}</div>
            <div class="label">Rata-rata Malam</div>
        </div>
    </div>

    <!-- Status Summary -->
    <div class="section">
        <div class="section-title">Distribusi Status Transaksi</div>
        <div class="summary-box">
            <div class="summary-row">
                <div class="summary-item pending">
                    <div class="count">{{ $data['statusStats']['pending'] }}</div>
                    <div class="label">Pending</div>
                </div>
                <div class="summary-item dikonfirmasi">
                    <div class="count">{{ $data['statusStats']['dikonfirmasi'] }}</div>
                    <div class="label">Dikonfirmasi</div>
                </div>
                <div class="summary-item dibayar">
                    <div class="count">{{ $data['statusStats']['dibayar'] }}</div>
                    <div class="label">Dibayar</div>
                </div>
                <div class="summary-item selesai">
                    <div class="count">{{ $data['statusStats']['selesai'] }}</div>
                    <div class="label">Selesai</div>
                </div>
                <div class="summary-item batal">
                    <div class="count">{{ $data['statusStats']['batal'] }}</div>
                    <div class="label">Batal</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly/Daily Breakdown -->
    @if($periode === 'tahunan' && count($data['monthlyData']) > 0)
    <div class="section">
        <div class="section-title">Rekap Bulanan</div>
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['monthlyData'] as $md)
                <tr>
                    <td>{{ $md['bulan'] }}</td>
                    <td class="text-center">{{ $md['transaksi'] }}</td>
                    <td class="text-right">Rp {{ number_format($md['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight:bold; background:#f0f0f0">
                    <td>TOTAL</td>
                    <td class="text-center">{{ array_sum(array_column($data['monthlyData'], 'transaksi')) }}</td>
                    <td class="text-right">Rp {{ number_format(array_sum(array_column($data['monthlyData'], 'revenue')), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    @if($periode === 'bulanan' && count($data['dailyData']) > 0)
    <div class="section">
        <div class="section-title">Rekap Harian</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['dailyData'] as $dd)
                <tr>
                    <td>{{ $dd['tanggal'] }}</td>
                    <td class="text-center">{{ $dd['transaksi'] }}</td>
                    <td class="text-right">Rp {{ number_format($dd['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Top Rooms -->
    @if(count($data['topRooms']) > 0)
    <div class="section">
        <div class="section-title">Kamar Terpopuler</div>
        <table>
            <thead>
                <tr>
                    <th>No Kamar</th>
                    <th>Jenis</th>
                    <th class="text-center">Jumlah Dipesan</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['topRooms'] as $room)
                <tr>
                    <td>#{{ $room['no_kamar'] }}</td>
                    <td>{{ $room['jenis'] }}</td>
                    <td class="text-center">{{ $room['count'] }}x</td>
                    <td class="text-right">Rp {{ number_format($room['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Transaction List -->
    <div class="section">
        <div class="section-title">Daftar Transaksi</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengunjung</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th class="text-center">Kamar</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['transaksis'] as $t)
                <tr>
                    <td>#{{ $t->no_transaksi }}</td>
                    <td>{{ $t->pengunjung->nm_pengunjung ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_masuk)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_keluar)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $t->jmlh_kamar }}</td>
                    <td class="text-right">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh sistem HOTELX</p>
        <p>{{ $tanggalCetak }}</p>
    </div>
</body>
</html>

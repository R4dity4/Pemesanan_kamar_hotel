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
            font-size: 10px;
            line-height: 1.5;
            color: #2c3e50;
            padding: 20px;
        }
        .page {
            position: relative;
            min-height: 100vh;
            padding-bottom: 80px;
        }
        .header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: rgb(0, 0, 0);
            padding: 25px 30px;
            margin: -20px -20px 25px -20px;
            border-bottom: 5px solid #b78f5a;
        }
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .header-logo {
            display: table-cell;
            width: 30%;
            vertical-align: middle;
        }
        .header-logo h1 {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }
        .header-logo .accent {
            color: #b78f5a;
        }
        .header-info {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
            text-align: right;
        }
        .header-info p {
            font-size: 9px;
            margin: 2px 0;
            opacity: 0.9;
        }
        .header-bottom {
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 12px;
            text-align: center;
        }
        .header-bottom h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .header-bottom .periode {
            font-size: 11px;
            color: #b78f5a;
            font-weight: 600;
        }
        .metadata {
            background: #f8f9fa;
            border-left: 4px solid #b78f5a;
            padding: 12px 15px;
            margin-bottom: 25px;
            font-size: 9px;
        }
        .metadata table {
            width: 100%;
            border: none;
        }
        .metadata td {
            padding: 3px 0;
            border: none;
        }
        .metadata .label {
            color: #666;
            width: 120px;
        }
        .metadata .value {
            font-weight: bold;
            color: #2c3e50;
        }
        .executive-summary {
            background: linear-gradient(135deg, #b78f5a 0%, #9a7648 100%);
            color: rgb(0, 0, 0);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        .executive-summary h3 {
            font-size: 13px;
            margin-bottom: 15px;
            text-align: center;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 8px;
        }
        .metrics {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        .metric-card {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 12px;
            background: rgba(255,255,255,0.15);
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        .metric-card:last-child {
            border-right: none;
        }
        .metric-card .icon {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .metric-card .value {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
        }
        .metric-card .label {
            font-size: 8px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-header {
            background: #2c3e50;
            color: rgb(255, 255, 255);
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .status-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .status-item {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 15px 10px;
            background: #f8f9fa;
            border: 2px solid white;
        }
        .status-item .count {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .status-item .label {
            font-size: 8px;
            text-transform: uppercase;
            color: #666;
            letter-spacing: 0.5px;
        }
        .status-item.pending { background: #fff3cd; }
        .status-item.pending .count { color: #856404; }
        .status-item.dikonfirmasi { background: #d1ecf1; }
        .status-item.dikonfirmasi .count { color: #0c5460; }
        .status-item.dibayar { background: #d4edda; }
        .status-item.dibayar .count { color: #155724; }
        .status-item.selesai { background: #e2e3e5; }
        .status-item.selesai .count { color: #383d41; }
        .status-item.batal { background: #f8d7da; }
        .status-item.batal .count { color: #721c24; }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th {
            background: #34495e;
            color: rgb(255, 255, 255);
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #2c3e50;
        }
        table.data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        table.data-table tbody tr:nth-child(odd) {
            background: #f8f9fa;
        }
        table.data-table tbody tr:hover {
            background: #e9ecef;
        }
        table.data-table tfoot td {
            background: #34495e;
            color: rgb(255, 255, 255);
            font-weight: bold;
            padding: 10px 8px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending { background: #ffc107; color: #000; }
        .badge-dikonfirmasi { background: #17a2b8; color: white; }
        .badge-dibayar { background: #28a745; color: white; }
        .badge-selesai { background: #6c757d; color: white; }
        .badge-batal { background: #dc3545; color: white; }
        .highlight-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin: 15px 0;
            font-size: 9px;
        }
        .highlight-box strong {
            color: #856404;
        }
        .chart-bar {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        .chart-bar .bar-label {
            display: table-cell;
            width: 80px;
            padding-right: 10px;
            text-align: right;
            font-size: 9px;
            vertical-align: middle;
        }
        .chart-bar .bar-container {
            display: table-cell;
            vertical-align: middle;
        }
        .chart-bar .bar {
            background: linear-gradient(90deg, #b78f5a 0%, #d4a574 100%);
            height: 18px;
            border-radius: 3px;
            position: relative;
        }
        .chart-bar .bar-value {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 8px;
            font-weight: bold;
        }
        .two-column {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }
        .column:first-child {
            border-right: 2px solid #ddd;
        }
        .revenue-box {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: rgb(0, 0, 0);
            padding: 20px;
            text-align: center;
            border-radius: 4px;
            margin: 15px 0;
        }
        .revenue-box .title {
            font-size: 10px;
            opacity: 0.9;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .revenue-box .amount {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .revenue-box .detail {
            font-size: 8px;
            opacity: 0.85;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f8f9fa;
            border-top: 3px solid #b78f5a;
            padding: 15px 30px;
            font-size: 8px;
        }
        .footer-content {
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
        }
        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 180px;
        }
        .signature-box .title {
            font-size: 9px;
            margin-bottom: 50px;
        }
        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 10px;
        }
        .signature-box .position {
            font-size: 8px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="header-logo">
                    <h1>HOTEL<span class="accent">X</span></h1>
                </div>
                <div class="header-info">
                    <p><strong>Alamat:</strong> Jl. Bukit Watuwila VI No.26, Bringin, Ngaliyan, Semarang</p>
                    <p><strong>Telp:</strong> +62 00 2606 2007 | <strong>Email:</strong> info@hotelx.id</p>
                    <p><strong>Website:</strong> www.hotelx.id</p>
                </div>
            </div>
            <div class="header-bottom">
                <h2>LAPORAN TRANSAKSI & ANALISIS BISNIS</h2>
                <div class="periode">{{ $judulPeriode }}</div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="metadata">
            <table>
                <tr>
                    <td class="label">Tanggal Cetak:</td>
                    <td class="value">{{ $tanggalCetak }}</td>
                    <td class="label">Jenis Laporan:</td>
                    <td class="value">{{ ucfirst($periode) }}</td>
                </tr>
                <tr>
                    <td class="label">Dicetak Oleh:</td>
                    <td class="value">{{ session('karyawan')->nm_karyawan ?? 'Administrator' }}</td>
                    <td class="label">Departemen:</td>
                    <td class="value">Finance & Accounting</td>
                </tr>
            </table>
        </div>

        <!-- Executive Summary -->
        <div class="executive-summary">
            <h3>EXECUTIVE SUMMARY</h3>
            <div class="metrics">
                <div class="metric-card">
                    <div class="value">{{ $data['totalTransaksi'] }}</div>
                    <div class="label">Total Transaksi</div>
                </div>
                <div class="metric-card">
                    <div class="value">Rp {{ number_format($data['totalRevenue'] / 1000000, 1) }}M</div>
                    <div class="label">Pendapatan Terkonfirmasi</div>
                </div>
                <div class="metric-card">
                    <div class="value">{{ $data['totalKamarDipesan'] }}</div>
                    <div class="label">Total Room Nights</div>
                </div>
                <div class="metric-card">
                    <div class="value">{{ $data['avgLamaNginap'] }}</div>
                    <div class="label">Avg Length of Stay</div>
                </div>
            </div>
        </div>

        <!-- Revenue Details -->
        <div class="section">
            <div class="section-header">ANALISIS PENDAPATAN</div>
            <div class="two-column">
                <div class="column">
                    <div class="revenue-box" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        <div class="title">Pendapatan Terkonfirmasi</div>
                        <div class="amount">Rp {{ number_format($data['totalRevenue'], 0, ',', '.') }}</div>
                        <div class="detail">Dari transaksi dengan status Dibayar & Selesai</div>
                    </div>
                </div>
                <div class="column">
                    <div class="revenue-box" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);">
                        <div class="title">Potensi Pendapatan</div>
                        <div class="amount">Rp {{ number_format($data['potentialRevenue'], 0, ',', '.') }}</div>
                        <div class="detail">Dari transaksi dengan status Pending & Dikonfirmasi</div>
                    </div>
                </div>
            </div>

            @php
                $totalPotential = $data['totalRevenue'] + $data['potentialRevenue'];
                $conversionRate = $totalPotential > 0 ? ($data['totalRevenue'] / $totalPotential * 100) : 0;
            @endphp
            <div class="highlight-box">
                <strong>Conversion Rate:</strong> {{ number_format($conversionRate, 1) }}%
                dari total Rp {{ number_format($totalPotential, 0, ',', '.') }}
                ({{ number_format($data['totalRevenue'], 0, ',', '.') }} terkonfirmasi + {{ number_format($data['potentialRevenue'], 0, ',', '.') }} pending)
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="section">
            <div class="section-header">DISTRIBUSI STATUS TRANSAKSI</div>
            <div class="status-grid">
                <div class="status-item pending">
                    <div class="count">{{ $data['statusStats']['pending'] }}</div>
                    <div class="label">Pending</div>
                </div>
                <div class="status-item dikonfirmasi">
                    <div class="count">{{ $data['statusStats']['dikonfirmasi'] }}</div>
                    <div class="label">Dikonfirmasi</div>
                </div>
                <div class="status-item dibayar">
                    <div class="count">{{ $data['statusStats']['dibayar'] }}</div>
                    <div class="label">Dibayar</div>
                </div>
                <div class="status-item selesai">
                    <div class="count">{{ $data['statusStats']['selesai'] }}</div>
                    <div class="label">Selesai</div>
                </div>
                <div class="status-item batal">
                    <div class="count">{{ $data['statusStats']['batal'] }}</div>
                    <div class="label">Batal</div>
                </div>
            </div>
        </div>

        <!-- Monthly/Daily Breakdown -->
        @if($periode === 'tahunan' && count($data['monthlyData']) > 0)
        <div class="section">
            <div class="section-header">REKAP BULANAN</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th class="text-center">Jumlah Transaksi</th>
                        <th class="text-right">Pendapatan</th>
                        <th class="text-right">% Kontribusi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalYearRevenue = array_sum(array_column($data['monthlyData'], 'revenue')); @endphp
                    @foreach($data['monthlyData'] as $md)
                    <tr>
                        <td><strong>{{ $md['bulan'] }}</strong></td>
                        <td class="text-center">{{ $md['transaksi'] }}</td>
                        <td class="text-right">Rp {{ number_format($md['revenue'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ $totalYearRevenue > 0 ? number_format(($md['revenue'] / $totalYearRevenue * 100), 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-center">{{ array_sum(array_column($data['monthlyData'], 'transaksi')) }}</td>
                        <td class="text-right">Rp {{ number_format($totalYearRevenue, 0, ',', '.') }}</td>
                        <td class="text-right">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        @if($periode === 'bulanan' && count($data['dailyData']) > 0)
        <div class="section">
            <div class="section-header">REKAP HARIAN</div>
            <table class="data-table">
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
                        <td><strong>Tanggal {{ $dd['tanggal'] }}</strong></td>
                        <td class="text-center">{{ $dd['transaksi'] }}</td>
                        <td class="text-right">Rp {{ number_format($dd['revenue'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>TOTAL</td>
                        <td class="text-center">{{ array_sum(array_column($data['dailyData'], 'transaksi')) }}</td>
                        <td class="text-right">Rp {{ number_format(array_sum(array_column($data['dailyData'], 'revenue')), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Top Rooms -->
        @if(count($data['topRooms']) > 0)
        <div class="section">
            <div class="section-header">KAMAR TERPOPULER</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="15%">No Kamar</th>
                        <th width="30%">Jenis</th>
                        <th class="text-center" width="20%">Jumlah Dipesan</th>
                        <th class="text-right" width="20%">Pendapatan</th>
                        <th class="text-center" width="15%">Popularitas</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxCount = max(array_column($data['topRooms'], 'count')); @endphp
                    @foreach($data['topRooms'] as $index => $room)
                    <tr>
                        <td><strong>#{{ $room['no_kamar'] }}</strong></td>
                        <td>{{ $room['jenis'] }}</td>
                        <td class="text-center">{{ $room['count'] }}x</td>
                        <td class="text-right">Rp {{ number_format($room['revenue'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $stars = ceil(($room['count'] / $maxCount) * 5);
                                echo str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
                            @endphp
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- PAGE BREAK -->
    <div class="page-break"></div>

    <!-- Page 2: Transaction Details -->
    <div class="page">
        <div class="section">
            <div class="section-header">DAFTAR TRANSAKSI DETAIL</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="8%">No.Trx</th>
                        <th width="20%">Pengunjung</th>
                        <th width="12%">Check-in</th>
                        <th width="12%">Check-out</th>
                        <th width="8%" class="text-center">Kamar</th>
                        <th width="8%" class="text-center">Malam</th>
                        <th width="17%" class="text-right">Total</th>
                        <th width="15%" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['transaksis'] as $t)
                    <tr>
                        <td><strong>#{{ $t->no_transaksi }}</strong></td>
                        <td>{{ $t->pengunjung->nm_pengunjung ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->tgl_masuk)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->tgl_keluar)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $t->jmlh_kamar }}</td>
                        <td class="text-center">{{ $t->lama_nginap }}</td>
                        <td class="text-right">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada transaksi pada periode ini</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>TOTAL</strong></td>
                        <td class="text-center"><strong>{{ $data['totalKamarDipesan'] }}</strong></td>
                        <td class="text-center">-</td>
                        <td class="text-right"><strong>Rp {{ number_format($data['totalRevenue'] + $data['potentialRevenue'], 0, ',', '.') }}</strong></td>
                        <td class="text-center"><strong>{{ $data['totalTransaksi'] }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="title">Menyetujui,</div>
                <div class="name">{{ session('karyawan')->nm_karyawan ?? '_________________' }}</div>
                <div class="position">Manager</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <p><strong>HOTELX</strong> - Sistem Manajemen Hotel Terpadu</p>
                <p>Dokumen ini digenerate secara otomatis dan bersifat rahasia</p>
            </div>
            <div class="footer-right">
                <p><strong>Dicetak:</strong> {{ $tanggalCetak }}</p>
                <p><strong>Oleh:</strong> {{ session('karyawan')->nm_karyawan ?? 'Administrator' }}</p>
            </div>
        </div>
    </div>
</body>
</html>

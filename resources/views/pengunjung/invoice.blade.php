<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaksi->no_transaksi }} - Hotel X</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .invoice-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .hotel-brand h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 3px;
        }
        .hotel-brand h1 span {
            color: #b78f5a;
        }
        .hotel-brand p {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 20px;
            font-weight: 300;
            letter-spacing: 4px;
        }
        .invoice-title .invoice-number {
            font-size: 11px;
            margin-top: 2px;
            opacity: 0.9;
        }
        .invoice-body {
            padding: 20px 28px 16px;
        }
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 14px;
        }
        .info-section h3 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #999;
            margin-bottom: 6px;
            border-bottom: 2px solid #b78f5a;
            padding-bottom: 4px;
            display: inline-block;
        }
        .info-section p {
            line-height: 1.5;
            font-size: 11px;
        }
        .info-section p strong {
            color: #1a1a1a;
        }
        .reservation-details {
            background: #fafafa;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 14px;
        }
        .reservation-details h3 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #999;
            margin-bottom: 8px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .detail-item label {
            display: block;
            font-size: 9px;
            color: #999;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .detail-item span {
            font-size: 12px;
            font-weight: 600;
            color: #1a1a1a;
        }
        .detail-item span.highlight {
            color: #b78f5a;
        }
        .time-notice {
            background: #fff8e1;
            border-left: 3px solid #b78f5a;
            border-radius: 0 4px 4px 0;
            padding: 8px 12px;
            margin-bottom: 14px;
            display: flex;
            gap: 16px;
            align-items: center;
        }
        .time-notice p {
            color: #7a6a3a;
            font-size: 10px;
            line-height: 1.4;
        }
        .time-notice strong {
            color: #5a4e2a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        thead tr {
            background: #1a1a1a;
            color: white;
        }
        th {
            padding: 7px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        th:last-child {
            text-align: right;
        }
        td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        td:last-child {
            text-align: right;
            font-weight: 500;
        }
        .layanan-title {
            margin: 8px 0 4px;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .table-footer {
            margin-bottom: 12px;
        }
        .table-footer .row {
            display: flex;
            justify-content: flex-end;
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }
        .table-footer .row.total {
            background: #1a1a1a;
            color: white;
            border: none;
            margin-top: 2px;
        }
        .table-footer .row label {
            min-width: 120px;
            text-align: right;
            margin-right: 20px;
            font-size: 11px;
        }
        .table-footer .row span {
            min-width: 130px;
            text-align: right;
            font-weight: 600;
            font-size: 11px;
        }
        .table-footer .row.total span {
            font-size: 14px;
            color: #b78f5a;
        }
        .table-footer .row.total label {
            font-size: 12px;
            padding-top: 1px;
        }
        .payment-status {
            text-align: center;
            padding: 10px;
            background: #d4edda;
            border-radius: 6px;
            margin-bottom: 0;
        }
        .payment-status.paid {
            background: #d4edda;
        }
        .payment-status h3 {
            color: #28a745;
            font-size: 14px;
            margin-bottom: 2px;
        }
        .payment-status p {
            color: #155724;
            font-size: 10px;
        }
        .invoice-footer {
            text-align: center;
            padding: 12px 28px;
            background: #fafafa;
            border-top: 2px solid #eee;
        }
        .invoice-footer p {
            font-size: 10px;
            color: #999;
            line-height: 1.5;
        }
        .invoice-footer .contact {
            margin-top: 6px;
            font-size: 10px;
            color: #666;
        }
        .print-buttons {
            text-align: center;
            margin-bottom: 16px;
        }
        .print-buttons button {
            background: #b78f5a;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 13px;
            cursor: pointer;
            border-radius: 4px;
            margin: 0 6px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .print-buttons button:hover {
            background: #9a7648;
        }
        .print-buttons button.secondary {
            background: #666;
        }
        .print-buttons button.secondary:hover {
            background: #444;
        }
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 11px;
            }
            .invoice-container {
                box-shadow: none;
                max-width: 100%;
            }
            .print-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-buttons">
        <button onclick="window.print()">🖨️ Cetak Invoice</button>
        <button class="secondary" onclick="window.close()">✕ Tutup</button>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="hotel-brand">
                <h1>HOTEL<span>X</span></h1>
                <p>Luxury Hotel & Resort</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">#INV-{{ str_pad($transaksi->no_transaksi, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <div class="invoice-body">
            <div class="invoice-info">
                <div class="info-section">
                    <h3>Ditagihkan Kepada</h3>
                    <p>
                        <strong>{{ $transaksi->pengunjung->nm_pengunjung }}</strong><br>
                        {{ $transaksi->pengunjung->alamat }}<br>
                        Telp: {{ $transaksi->pengunjung->no_tlp }} &nbsp;|&nbsp; KTP: {{ $transaksi->pengunjung->no_ktp }}
                    </p>
                </div>
                <div class="info-section">
                    <h3>Informasi Invoice</h3>
                    <p>
                        <strong>Tanggal:</strong> {{ date('d F Y') }}<br>
                        <strong>No. Transaksi:</strong> {{ $transaksi->no_transaksi }}<br>
                        <strong>Status:</strong>
                        @if($transaksi->status == 'dibayar')
                            Dikonfirmasi
                        @elseif($transaksi->status == 'selesai')
                            Selesai
                        @endif
                        @if($transaksi->karyawan)
                        &nbsp;oleh {{ $transaksi->karyawan->nm_karyawan }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="reservation-details">
                <h3>Detail Reservasi</h3>
                <div class="detail-grid">
                    <div class="detail-item">
                        <label>Check-in</label>
                        <span>{{ date('d M Y', strtotime($transaksi->tgl_masuk)) }}</span>
                        <span class="highlight" style="display:block; font-size:10px">14:00 WIB</span>
                    </div>
                    <div class="detail-item">
                        <label>Check-out</label>
                        <span>{{ date('d M Y', strtotime($transaksi->tgl_keluar)) }}</span>
                        <span class="highlight" style="display:block; font-size:10px">12:00 WIB</span>
                    </div>
                    <div class="detail-item">
                        <label>Lama Menginap</label>
                        <span>{{ $transaksi->lama_nginap }} Malam</span>
                    </div>
                    <div class="detail-item">
                        <label>Jumlah Kamar</label>
                        <span>{{ $transaksi->jmlh_kamar }} Kamar</span>
                    </div>
                </div>
            </div>

            <div class="time-notice">
                <p>
                    <strong>Check-in:</strong> Mulai pukul 14:00 WIB &nbsp;•&nbsp;
                    <strong>Check-out:</strong> Paling lambat pukul 12:00 WIB. Keterlambatan dapat dikenakan biaya tambahan.
                </p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No. Kamar</th>
                        <th>Jenis Kamar</th>
                        <th>Durasi</th>
                        <th>Harga/Malam</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotalKamar = 0; @endphp
                    @foreach($transaksi->detailTransaksi as $detail)
                    @php $subtotalKamar += ($detail->kamar->harga ?? 0) * $transaksi->lama_nginap; @endphp
                    <tr>
                        <td><strong>{{ $detail->no_kamar }}</strong></td>
                        <td>{{ $detail->kamar->jenis_kamar ?? '-' }}</td>
                        <td>{{ $transaksi->lama_nginap }} malam</td>
                        <td>Rp {{ number_format($detail->kamar->harga ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format(($detail->kamar->harga ?? 0) * $transaksi->lama_nginap, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($transaksi->transaksiLayanan && $transaksi->transaksiLayanan->count() > 0)
            <h4 class="layanan-title">Layanan Tambahan</h4>
            <table>
                <thead>
                    <tr>
                        <th>Layanan</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th colspan="2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotalLayanan = 0; @endphp
                    @foreach($transaksi->transaksiLayanan as $tl)
                    @php $subtotalLayanan += $tl->subtotal; @endphp
                    <tr>
                        <td><strong>{{ $tl->layanan->nama_layanan ?? '-' }}</strong></td>
                        <td>{{ $tl->jumlah }}x</td>
                        <td>Rp {{ number_format($tl->layanan->harga ?? 0, 0, ',', '.') }}</td>
                        <td colspan="2">Rp {{ number_format($tl->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            @php $subtotalLayanan = 0; @endphp
            @endif

            <div class="table-footer">
                <div class="row">
                    <label>Subtotal Kamar</label>
                    <span>Rp {{ number_format($subtotalKamar, 0, ',', '.') }}</span>
                </div>
                @if($subtotalLayanan > 0)
                <div class="row">
                    <label>Subtotal Layanan</label>
                    <span>Rp {{ number_format($subtotalLayanan, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="row">
                    <label>Pajak (0%)</label>
                    <span>Rp 0</span>
                </div>
                <div class="row total">
                    <label>TOTAL</label>
                    <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="payment-status paid">
                <h3>✓ LUNAS</h3>
                <p>Pembayaran telah dikonfirmasi. Terima kasih atas kepercayaan Anda menginap di Hotel X.</p>
            </div>
        </div>

        <div class="invoice-footer">
            <p>Invoice ini sah dan dikeluarkan secara elektronik. Harap tunjukkan invoice ini saat check-in.</p>
            <div class="contact">
                Jl. Bukit Watuwila VI No.26 Bringin, Kec. Ngaliyan Kota Semarang | Tel: +62 00 2606 2007 | Email: reservasi@hotelx.com
            </div>
        </div>
    </div>
</body>
</html>
